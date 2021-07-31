<?php
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/core/Encryption.php');

class SmsMinify
{
    const USER_SMS_URL=1;
    const NANO_APP_LINK=2;
    private static $dv;
    private $db_connection = null;

    public function __construct()
    {
        SmsMinify::$dv = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
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
    private function getNext($cur)
    {
        $cv = str_split($cur);
        for ($i = count($cv) - 1; $i > - 1; $i --) {
            if ($cv[$i] == "_") {
                if ($i == 0) {
                    $cv = array_fill(0, count($cv) + 1, 0);
                    return implode("", $cv);
                } else {
                    if ($cv[$i - 1] != '_') {
                        $cv[$i - 1] = SmsMinify::$dv[array_search($cv[$i - 1], SmsMinify::$dv) + 1];
                        for ($j = $i; $j < count($cv); $j ++) {
                            $cv[$j] = 0;
                        }
                        return implode("", $cv);
                    }
                }
            } else {
                $cv[$i] = SmsMinify::$dv[array_search($cv[$i], SmsMinify::$dv) + 1];
                if ($i == 0) {
                    $next = array_fill(0, count($cv), 0);
                    $next[0] = $cv[$i];
                    $cv = $next;
                }
                return implode("", $cv);
            }
        }
    }

    public function createTextChatUrl($user_id, $bot_id){//bot_id=66baba46621&user_id=95
        $params = "bot_id=".$bot_id."&user_id=".$user_id;
        list($key, $str) = Encryption::encrypt($params);
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare(sprintf("insert into url_param_lookup values('5', '%s', 4, '%s', '%s', now()) ON DUPLICATE KEY UPDATE str='%s', updated=now();",
                    $bot_id, $key, $str, $str));
            $result = $sth->execute();
        }
        return "1do.in/5,".$key;
    }
    public function createChatUrl($user_id, $bot_id){//bot_id=66baba46621&user_id=95
        $params = "bot_id=".$bot_id."&user_id=".$user_id;
        list($key, $str) = Encryption::encrypt($params);
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare(sprintf("insert into url_param_lookup values('6', '%s', 8, '%s', '%s', now()) ON DUPLICATE KEY UPDATE str='%s', updated=now();",
                    $bot_id, $key, $str, $str));
            $result = $sth->execute();
        }
        return "1do.in/6,".$key;
    }
    public function createMicroAppUrl($user_id, $bot_id){//bot_id=66baba46621&user_id=95
        $params = "bot_id=".$bot_id."&user_id=".$user_id;
        list($key, $str) = Encryption::encrypt($params);
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare(sprintf("insert into url_param_lookup values('7', '%s', 4, '%s', '%s', now()) ON DUPLICATE KEY UPDATE str='%s', updated=now();",
                    $bot_id, $key, $str, $str));
            $result = $sth->execute();
        }
        return "1do.in/7,".$key;
    }
    public function createMicroAppUrlForUser($user_id, $bot_id, $there_number){//bot_id=66baba46621&user_id=95&there_number=9999999
        $params = "bot_id=".$bot_id."&user_id=".$user_id."&there_number=".$there_number."&time=".time ();
        list($key, $str) = Encryption::encrypt($params);
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare(sprintf("insert into url_param_lookup values('8', '%s', 1, '%s', '%s', now()) ON DUPLICATE KEY UPDATE str='%s', updated=now();",
                    $bot_id, $key, $str, $str));
            $result = $sth->execute();
        }
        return "1do.in/8,".$key;
    }
    public function createMicroAppUrlOtp($user_id, $bot_id){//bot_id=66baba46621&user_id=95
        $params = "bot_id=".$bot_id."&user_id=".$user_id."&app=r";
        list($key, $str) = Encryption::encrypt($params);
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare(sprintf("insert into url_param_lookup values('9', '%s', 2, '%s', '%s', now()) ON DUPLICATE KEY UPDATE str='%s', updated=now();",
                    $bot_id, $key, $str, $str));
            $result = $sth->execute();
        }
        return "1do.in/9,".$key;
    }
    public function createFileManagerUrlOtp(){//bot_id=66baba46621&user_id=95
        $params = "bot_id=awsfilemgr&user_id=95&app=file";
        list($key, $str) = Encryption::encrypt($params);
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare(sprintf("insert into url_param_lookup values('9', '%s', 3, '%s', '%s', now()) ON DUPLICATE KEY UPDATE str='%s', updated=now();",
                    "awsfilemgr", $key, $str, $str));
            $result = $sth->execute();
        }
        return "1do.in/9,".$key;
    }
    public function createSqliteManagerUrlOtp(){//bot_id=66baba46621&user_id=95
        $params = "bot_id=sqlitemgr&user_id=95&app=sql";
        list($key, $str) = Encryption::encrypt($params);
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare(sprintf("insert into url_param_lookup values('9', '%s', 5, '%s', '%s', now()) ON DUPLICATE KEY UPDATE str='%s', updated=now();",
                    "sqlitemgr", $key, $str, $str));
            $result = $sth->execute();
        }
        return "1do.in/9,".$key;
    }
    public function createOwnerUrl($user_id, $bot_id, $number, $email){//bot_id=66baba46621&user_id=95
        $params = "bot_id=".$bot_id."&user_id=".$user_id."&number=".$number."&email=".$email."&app=b";
        list($key, $str) = Encryption::encrypt($params);
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare(sprintf("insert into url_param_lookup values('4', '%s', 4, '%s', '%s', now()) ON DUPLICATE KEY UPDATE str='%s', updated=now();",
                    $bot_id, $key, $str, $str));
            $result = $sth->execute();
        }
        return "1do.in/4,".$key;
    }
    public function lookup($map_id, $lookup){
        $result=null;
        if ($this->databaseConnection()) {
            $lp = $this->db_connection->quote($lookup);
            $mid = $this->db_connection->quote($map_id);
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare(sprintf("select str from url_param_lookup where map_id=%s and lookup=%s", $mid, $lp));
            $sth->execute();
            $result = $sth->fetch()[0];
            error_log("key=".$lookup.", str=".$result);
            if (!isset($result)){
                echo '<h1>This Micro Web was removed, forwarding you to App Dashboard.</h1>';
                echo '<meta http-equiv="refresh" content="2; URL=https://1do.in" />';
                die;
            }
        }
        $dec = Encryption::decrypt($lookup, $result);
        error_log("Decoded params lookup = ".$dec);
        return $dec;
    }


    private function getLastIndex(){
        $result=array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select id from url_map order by id desc limit 1');
            $sth->execute();
            $result = $sth->fetch()[0];
            error_log("getLastIndex Error=".implode(",", $sth->errorInfo()));
            error_log("Result=".$result);
            if ($result==null){
                return "0";
            }
        }
        return $result;
    }

    public function createMap($url, $user_id){
        $li = $this->getLastIndex();
        error_log("Li=".$li);
        $id = $this->getNext($li);
        error_log("Id=".$id);
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('insert into url_map(id, url, user_id) values(:id, :url, :user_id)');
            $sth->bindValue(':id',  $id, PDO::PARAM_STR);
            $sth->bindValue(':url', $url, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("createMap Error=".implode(",", $sth->errorInfo()));
        }
        return $id;
    }

    public function createMapForUserName($url, $user_id, $user_name){
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('replace into url_map(id, url, user_id) values(:id, :url, :user_id)');
            $sth->bindValue(':id',  $user_name, PDO::PARAM_STR);
            $sth->bindValue(':url', $url, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("createMapForUserName Error=".implode(",", $sth->errorInfo()));
        }
    }
    public function updateUrl($user_id, $url_id, $url)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('update url_map set url=:url, removed=NULL where id=:url_id and user_id=:user_id');
            $sth->bindValue(':url_id', $url_id, PDO::PARAM_STR);
            $sth->bindValue(':url', $url, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("deleteTrigger Error=" . implode(",", $sth->errorInfo()));
        }
    }

    public function getUrl($map_name){
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select url from url_map where id=:id and removed is NULL');
            $sth->bindValue(':id',  $map_name, PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch()[0];
            //error_log("getUrl Error=".implode(",", $sth->errorInfo()));
            //error_log("Result=".$result);
        }
        return $result;
    }
    public function getUrlForUser($user_id, $map_name){
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select url from url_map where id=:id and user_id=:user_id and removed is NULL');
            $sth->bindValue(':id',  $map_name, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch()[0];
            //error_log("getUrl Error=".implode(",", $sth->errorInfo()));
            //error_log("Result=".$result);
        }
        return $result;
    }

    public function getMappedUrlFor($url, $user_id, $user_name){
        $mapped_url = $this->getUrlForUser($user_id, $user_name);
        if (trim($mapped_url) == $url) {
            //already mapped
            $url = "https://1do.in/".$user_name;
            return array($url, true);
        }
        return array($url, false);
    }

    public function getUrlCount($user_id){
        $result=0;
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select count(*) from url_map where user_id=:user_id');
            $sth->bindValue(':user_id',  $user_id, PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch()[0];
            error_log("getUrl Error=".implode(",", $sth->errorInfo()));
            error_log("Result=".$result);
        }
        return $result;
    }
    public function getMapList($user_id){
        $result=array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from url_map where user_id=:user_id');
            $sth->bindValue(':user_id',  $user_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("getMapList Error=" . implode(",", $sth->errorInfo()));
            while($obj =  $sth->fetch()){
                $result[]=$obj;
            }
            error_log("getMapList Error=".implode(",", $sth->errorInfo()));
        }
        return $result;
    }

    public function deleteUrl($user_id, $url_id)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('update url_map set removed=now() where id=:url_id and user_id=:user_id');
            $sth->bindValue(':url_id', $url_id, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("deleteUrl Error=" . implode(",", $sth->errorInfo()));
        }
    }

    public function logAccess($id, $ip, $ua){
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('insert into url_log(id, ip, ua, createdOn) values(:id, :ip, :ua, now())');
            $sth->bindValue(':id',  $id, PDO::PARAM_STR);
            $sth->bindValue(':ip', $ip, PDO::PARAM_STR);
            $sth->bindValue(':ua', $ua, PDO::PARAM_STR);
            $sth->execute();
            //error_log("logAccess Error=".implode(",", $sth->errorInfo()));
        }
    }

    public function getAccess($id){
        $result=array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from url_log where id=:id');
            $sth->bindValue(':id',  $id, PDO::PARAM_STR);
            $sth->execute();
            error_log("getAccess Error=".implode(",", $sth->errorInfo()));
            while($obj =  $sth->fetch()){
                $result[]=$obj;
            }
        }
        return $result;
    }

    public function logHits($id){
        $result=array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from url_log where id=:id');
            $sth->bindValue(':id',  $id, PDO::PARAM_STR);
            $sth->execute();
            error_log("logHits Error=" . implode(",", $sth->errorInfo()));
            while($obj =  $sth->fetch()){
                $result[]=$obj;
            }
            error_log("Result=".count($result));
        }
        return count($result);
    }
}

?>