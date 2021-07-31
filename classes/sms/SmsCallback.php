<?php
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/libraries/password_compatibility_library.php');
require_once (__ROOT__ . '/libraries/aws.phar');
require_once(__ROOT__.'/config/config.php');

class SmsCallback
{
    private $db_connection = null;
    
    public function __construct()
    {
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
    /////////////////////////////////////// RESPONSE CALLBACK///////////////////////////////////
    
    public function getCallback($bot_id)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select url, respond from sms_callback where bot_id=:bot_id');
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("getCallback Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
            return $sth->fetch();
        }
    }
    
    public function addCallback($bot_id, $url, $respond)
    {
        if ($this->databaseConnection()) {
            $sth = $this->db_connection->prepare('insert into sms_callback(bot_id, url, respond, changedOn)  values (:bot_id, :url, :respond, now())'.
                ' on duplicate key update url = :url, respond = :respond, changedOn = now()');
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->bindValue(':url', $url, PDO::PARAM_STR);
            $sth->bindValue(':respond', $respond, PDO::PARAM_STR);
            $sth->execute();
            error_log("addCallback Error=" . implode(",", $sth->errorInfo()).$sth->queryString);
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
   
    
    public function removeCallback($bot_id, $url)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('delete from sms_callback  where bot_id=:bot_id');
            $sth->bindValue(':user_id', $bot_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("removeCallback Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
    
}
?>