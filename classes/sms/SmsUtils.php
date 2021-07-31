<?php
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/libraries/password_compatibility_library.php');
class SmsUtils
{

    public static $dt;
    private static $dv;
    private static $ld;
    private static $kl;
    const bucket = 'data.simonline';
    const bot_url_request_prefix = "Send me the link to ";
    const bot_url_request_prefix_len = 20;

    private $db_connection = null;
    
    public function __construct()
    {
        SmsUtils::$dv = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        SmsUtils::$ld = count(SmsUtils::$dv);
        SmsUtils::$kl = 64;
    }
    
    public function getTomorrow(){
        $now=Utils::datetimeNow("Asia/Calcutta");
        $date = Utils::$dt->setTimeStamp(strtotime('+1 day', $now));
        return  $date->format(DateTime::ATOM);
    }
    
    public function getOneWeekBack(){
        $now=Utils::datetimeNow("Asia/Calcutta");
        $date = Utils::$dt->setTimeStamp(strtotime('-1 week', $now));
        return  $date->format(DateTime::ATOM);
    }

    private function databaseConnection()
    {
        // if connection already exists
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                $this->db_connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $_SESSION['message']  = MESSAGE_DATABASE_ERROR . $e->getMessage();
            }
        }
        return false;
    }
    
    public function templateReplace($template, $data){
        if (preg_match_all("/{{(.*?)}}/", $template, $m)) {
            foreach ($m[1] as $i => $varname) {
                $template = str_replace($m[0][$i], sprintf('%s', $data[$varname]), $template);
            }
        }
        return $template;
    }
    
    public function isOtpRequired($template){
        if(strpos($template,  "{{otp}}") !== false){
            error_log("isOtpRequired TRUE ".$template);
            return true;
        } else{
            error_log("isOtpRequired FALSE ".$template);
            return false;
        }
    }
    public function templateInputFields($template){
        $fields=array();
        if (preg_match_all("/{{(.*?)}}/", $template, $m)) {
            foreach ($m[1] as $i => $varname) {
                error_log($i."Field = " . $varname);
                $fields[]=$varname;
            }
        }
        return $fields;
    }
    public function getNewKey()
    {
        $cv = "";
        for ($i = 0; $i < SmsUtils::$kl; $i ++) {
            $cv = $cv . SmsUtils::$dv[rand(0, SmsUtils::$ld - 1)];
        }
        return $cv;
    }


    public function createHostKey($user_id)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $hk = $this->getNewKey();
            $exp = date(DateTime::ATOM, strtotime('+9 month'));
            $sth = $this->db_connection->prepare('insert into host_key(user_id, host_key, expiringOn, changedOn)  values(:user_id, :host_key, :expiringOn, now())');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':host_key', $hk, PDO::PARAM_STR);
            $sth->bindValue(':expiringOn', $exp, PDO::PARAM_STR);
            $sth->execute();
            error_log("createHostKey Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return array(
            $hk,
            $exp
        );
    }

    public function getHostKey($user_id)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select host_key, expiringOn from host_key where user_id=:user_id');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            $val = $sth->fetch();
            error_log(print_r($val, true));
            $hk = $val[0];
            $exp = $val[1];
            error_log("getHostKey Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return array(
            $hk,
            $exp
        );
    }
    
    public function getHostKeyForUsername($user_name)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select host_key, expiringOn from host_key, users where users.user_name=:user_name and host_key.user_id=users.user_id');
            $sth->bindValue(':user_name', $user_name, PDO::PARAM_STR);
            $sth->execute();
            $val = $sth->fetch();
            error_log(print_r($val, true));
            $hk = $val[0];
            $exp = $val[1];
            error_log("getHostKey Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return array(
                $hk,
                $exp
        );
    }
    public function isHostKeyValid($user_id, $host_key)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select expiringOn from host_key where host_key=:host_key and user_id=:user_id');
            $sth->bindValue(':host_key', $host_key, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            $val = $sth->fetch();
            error_log($user_id." Result=".print_r($val, true));
            $exp = $val[0];
            error_log($sth->queryString." isHostKeyValid Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
            
            //Cur=1594786880 exp=1618318801
            if ($exp != null){  
                $exp = intval(strtotime($exp));
                $cur = intval(time());
                error_log("Cur=".$cur." exp=".$exp." minus=".($cur-$exp));
                if ($cur >  $exp){
                    return false;
                }
                else {
                    return true;
                }
            }
            else {
                return false;
            }
        }
    }
    
    public function checkHostKey($host_key)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select user_id, expiringOn from host_key where host_key=:host_key');
            $sth->bindValue(':host_key', $host_key, PDO::PARAM_STR);
            $sth->execute();
            $val = $sth->fetch();
            error_log(" Result=".print_r($val, true));
            $uid = $val[0];
            $exp = $val[1];
            error_log($sth->queryString." checkHostKey Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
            return array(
                    $uid,
                    $exp
            );
        }
        return null;
    }
    
    public function delHostKey($user_id)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('delete from host_key where user_id=:user_id');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("delHostKey Error=" . implode(",", $sth->errorInfo()));
        }
    }

    public function validateSms($sms){
        //$regex = '/(\@£$¥èéùìòÇ\fØø\nÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !#¤%&()*+,-.[0-9]:;<=>\?¡[A-Z]ÄÖÑÜ§¿[a-z]äöñüà\^\{\}\[~\]\|€)+/';
        $regex = '/([0-9A-Za-z\{\}].)/';
        if (preg_match($regex, $sms, $matches)){
            error_log(print_r($matches, true));
            return strlen($sms);
        }
        else {
            error_log("Validation failed");
            return 0;
        }  
    }
    
    public static function getSSLPage($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        #curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
    
}
?>