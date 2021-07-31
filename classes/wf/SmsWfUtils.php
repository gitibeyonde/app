<?php
require_once (__ROOT__ . '/classes/wf/data/SmsContext.php');
require_once (__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/sms/SmsMinify.php');
require_once (__ROOT__ . '/classes/sms/SmsImages.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/utils/Mobile_detect.php');


class SmsWfUtils {

    private $_log = null;

    public function __construct() {
        $this->_log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
    }

    public static function format($value, $type){
        if ($type == "datetime"){
            return array(date('j F \a\t H:i',strtotime($value)));
        }
        else {
            return array($value);
        }
    }

    public function fix_type_value($string, $type, $validate){
        switch($type){
            case "fixed":
                return $validate;
            case "string":
                if ($validate == "name"){
                    return $this->cleanName($string);
                }
                else if ($validate == "email"){
                    $email_pattern = '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i';
                    preg_match_all($email_pattern, $string, $matches);
                    $email = $matches[0][0];
                    $this->_log->trace("Email parsed=".$email);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        return null;
                    }
                    else {
                        return $email;
                    }
                }
                else if ($validate == "text"){
                    $string = preg_replace("/[^a-zA-Z0-9,.:\/\s]/", "", $string);
                    return $string;
                }
                else {
                    return $string;
                }
            case "number":
                if(preg_match_all("/([0-9]+)/", $string, $matches)){
                    return $matches[1][0];
                }
            case "integer":
                if(preg_match_all("/([+-]?[0-9]+)/", $string, $matches)){
                    return $matches[1][0];
                }
            case "decimal":
                if(preg_match_all("/([+-]?[0-9\.]+)/", $string, $matches)){
                    return $matches[1][0];
                }
            case "datetime":
                return $this->find_datetime($string);
            case "date":
                $this->_log->trace("Date=".$string);
                if ($validate == "date"){
                    return $this->find_date($string);
                }
                else if ($validate == "day_month"){
                    return $this->find_date($string);
                }
                else {
                    return $string;
                }
            default:
                throw new Exception("Unable to fix value of ".$string);
        }
    }
    private function cleanName($name_str){
        $this->_log->trace("Name is".$name_str);
        $pa = array("my name is", "name is", "hi my name is", "i am", "hi i am", "hi name is", "myself", "people call me");
        $name_str = $this->to_alphabet_string($name_str);
        foreach ($pa as $prefix){
            if (substr($name_str, 0, strlen($prefix)) == $prefix) {
                $name_str = substr($name_str, strlen($prefix));
                break;
            }
        }
        $this->_log->trace("Cleaned Name is".$name_str);
        return ucwords(trim($name_str));
    }

    public function find_datetime( $string ) {
        $string = str_replace(",", " at ", $string);
        if (strpos($string, "at") === False){
            return null;
        }
        $da =  date_parse($string);
        $this->_log->debug("Date array = ".SmsWfUtils::flatten($da));
        $now = new DateTime('now');
        if ($da['year'] == null){
            $da['year'] = $now->format('Y');
        }
        if ($da['month'] == null){
            $da['month'] =  $now->format('m');
        }
        if ($da['day'] == null){
            return null;
        }
        if ($da['hour'] == null){
            return null;
        }
        if ($da['minute'] == null || $da['minute']<10){
            $da['minute'] = "00";
        }
        $s = $da['day']."/".$da['month']."/".$da['year']." ".$da['hour'].":".$da['minute'];
        $this->_log->trace("FINDDATETIME string = $s");
        $date = DateTime::createFromFormat("d/m/Y H:i", $s);
        $this->_log->trace("FINDDATETIME date ".$date->format(DateTime::ATOM));
        return $date->format(DateTime::ATOM); //"D, d M H:i"
    }

    public function find_date( $string ) {
        $day = null;
        if(preg_match_all("/([0-9]+)/", $string, $matches)){
            $day = $matches[1][0];
        }
        else {
            return null;
        }
        $string = self::to_alphabet_string($string);
        $months = array( "january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december" );
        $max_days = array( 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $similarity = 0;
        $matched_index = -1;
        for ($i=0; $i< 12; $i++){
            $month=$months[$i];
            similar_text($string, $month, $sim);
            if ($similarity < $sim){
                $similarity = $sim;
                $matched_index = $i;
            }
        }
        $matched_month = $months[$matched_index];
        $maxd = $max_days[$matched_index];
        if ($day > $maxd){
            return null;
        }
        $this->_log->trace("FINDDATETIME day ".$matched_month);
        return $day." ".$matched_month; //"D, d M H:i"
    }
    private function clean_name($sms){
        $sms = preg_replace("/[^a-zA-Z0-9\s]/", "", $sms);
        return ucwords(rtrim(ltrim($sms, " "), " "));
    }
    private static function to_alphabet_string($sms){
        $sms = strtolower(preg_replace('/\s+/', ' ', $sms));
        $sms = preg_replace("/[^a-zA-Z\/\s]/", "", $sms);
        return $sms;
    }

    public function range($value, $type, $min, $max){
        if ($type == "number"){
            if ($value < $min[0]){
                return $min[3];
            }
            else if ($value > $max[0]){
                return $max[3];
            }
        }
        else if ($type == "string"){
            if (strlen($value) < $min[0]){
                return $min[3];
            }
            else if (strlen($value) > $max[0]){
                return $max[3];
            }
        }
        else if ($type == "datetime"){
            $now = new DateTime();
            $diff = $now->diff($value);
            if ($diff->d < $min[0]){
                return $min[3];
            }
            else if ($diff->d > $max[0]){
                return $max[3];
            }
        }
        return "";
    }

    public static function flatten($a){
        return "( ". SmsWfUtils::put_together($a, ", ", "(", ")")  . ")";
    }

    public static function join($a){
        return SmsWfUtils::put_together($a, '', '', '');
    }

    public static function put_together($a, $separator, $prefix, $suffix){
        $result = "";
        if (is_array($a)){
            foreach($a as $i){
                if (is_array($i)){
                    if ($result==null){
                        $result = SmsWfUtils::put_together($i, $separator, $prefix, $suffix);
                    }
                    else {
                        $result = $result .$separator .SmsWfUtils::put_together($i, $separator, $prefix, $suffix);
                    }
                }
                else {
                    if ($result==null){
                        $result = $result .$i;
                    }
                    else {
                        $result = $result.$separator .$i;
                    }
                }
            }
        }
        else {
            $result = $result .$a;
        }
        if (strpos($result, "/img/")){
            return $result;
        }
        else {
            return  $prefix. $result. $suffix;
        }
    }


    public static function processEmbeddedCommand($chat,  $user_id, $bot_id, $there_phone){
        $log = new Log("info");
        if (preg_match_all("/%%(.*?)%%/", $chat, $m)){
            //this is a command
            $cmd = $m[1][0];
            $context = new SmsContext($user_id, $bot_id);
            $log->trace("processEmbeddedCommand ".$cmd);
            if ($cmd == "CLEAR"){
                //clean user data and context
                $context->deleteContext($there_phone);
            }
            else if ($cmd == "CLEARALL"){
                //clean user data and context
                $context->deleteContext($there_phone);
                //$smsuser->deleteUserData($bot_id, $there_phone);
            }//DEL(drname,appointment)
            else {
                $log->debug("Illegal or unimplemented command ".$cmd);
            }
            return array(True, preg_replace("/%%(.*?)%%/", "", $chat));
        }
        return array(False, $chat);
    }


    public static function tranId($ip){
        $ip = ip2long($ip);
        $len = strlen($ip);
        if ($len > 11){
            $ip = $ip.substring(0, 11);
            error_log("IP=".$ip);
        }
        else {
            $ip = str_pad($ip, 11, "0", STR_PAD_LEFT);
            error_log("tranId PADDED IP=".$ip);
        }
        return "1".$ip;
    }

    public static function getPublicAppList(){
        $Md = new Mobile_Detect();
        $type = 1;
        if ($Md->isMobile()){
            $type=0;
        }

        $WFDB = new WfMasterDb();
        $sharedwf = $WFDB->getSharedWorkflow();

        $min = new SmsMinify();
        $SI = new SmsImages();

        include_once(__ROOT__ . '/classes/core/Icons.php');
        $Icon = new Icons();

        $html = 'Click <i class="ti-folder" style="color: blue;font-size: 2rem;"></i> for App. <i class="ti-comment-alt" style="color: red;font-size: 2rem;"></i>for Chat.';
        $html = $html . '<table class="table-striped" width="100%">';
       foreach ($sharedwf as $wf) {
            $lg = $SI->logo($wf['bot_id']);
            $url = $min->createMicroAppUrl(95, $wf['bot_id']);
            $curl = $min->createChatUrl(95, $wf['bot_id']);
            $tcurl = $min->createTextChatUrl(95, $wf['bot_id']);
            $html = $html."</tr>";
            $html = $html. "<tr>";
            $html = $html. "<td colspan=6 style='padding: 20px;'></td>";
            $html = $html. "</tr>";
            $html = $html. "<tr>";
            if ($type == 1){
                $html = $html. "<td style='padding: 10px;width: 15%;'><img class='img-fluid' src='".$lg."'></td>";
                $html = $html. "<td style='padding: 10px;width: 40%'><a href='https://".$url."'  target='_blank'><h4 class='selblock'>".$wf['name']."</h4></a></td>";
                $html = $html. "<td style='padding: 10px;width: 7%;'><a href='/sample_app.php?link=https://".$url."' target='_blank'><i class='ti-folder' style='color: blue;font-size: 2rem;'></i></a></td>";
                $html = $html. "<td style='padding: 10px;width: 7%;'><a href='/sample_chat.php?chat=https://".$curl."&tchat=https://".$tcurl."' target='_blank'><i class='ti-comment-alt' style='color: red;font-size: 2rem;'></i></a></td>";
            }
            else {
                $html = $html. "<td style='padding: 1px;width: 10%;'><img class='img-fluid' src='".$lg."'></td>";
                $html = $html. "<td style='padding: 10px;width: 40%'><a href='https://".$url."'  target='_blank'><h4 class='selblock'>".$wf['name']."</h4></a></td>";
                $html = $html. "<td style='padding: 10px;width: 7%;'><a href='https://".$url."'  target='_blank'><h5>".$Icon->get("folder", 1.5, "blue")."</h5></a></td>";
                $html = $html. "<td style='padding: 10px;width: 7%;'><a href='https://".$curl."'  target='_blank'><h5>".$Icon->get("chat_left", 1.5, "green")."</h5></a></td>";
            }
            $html = $html. "</tr>";
            $html = $html. "<tr>";
            $html = $html. "<td colspan=6 style='padding: 10px;'>".$wf['description']."</td>";
            $html = $html. "</tr>";
          }

        $html = $html.'</table>';
        return $html;
    }

}

?>