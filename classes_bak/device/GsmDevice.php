<?php
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once(__ROOT__.'/classes/User.php');
require_once(__ROOT__.'/classes/Utils.php');

class GsmDevice
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
    
    
    public function updateGsmDevice($uuid, $user_id, $my_number)
    {
        if ($this->databaseConnection()) {
            $user = new User($user_id);
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('insert into gsm_device(uuid, user_id, my_number, sent_count, changedOn) '.
                    'values(:uuid, :user_id,  :my_number, 0, now())  on duplicate key update changedOn=now(), user_id=VALUES(user_id), my_number=VALUES(my_number);');
            $sth->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user->user_id, PDO::PARAM_STR);
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->execute();
            error_log("updateGsmDevice Error=" . implode(",", $sth->errorInfo()).$user->user_id);
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
    public function getUnusedGsmDevice(){
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from gsm_device where rentee=0 and removed is NULL order by sent_count asc limit 1');
            $sth->execute();
            error_log("getUnusedGsmDevice Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
            return $sth->fetch();
        }
    }
    public function getUnusedGsmDevices(){
        $stmt = array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from gsm_device where rentee=0 and removed is NULL order by sent_count asc limit 20');
            $sth->execute();
            while($obj =  $sth->fetch()){
                $stmt[]=$obj;
            }
            error_log("getUnusedGsmDevice Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    
    public function getGsmDeviceFromNumber($phone){
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from gsm_device where my_number=:phone and removed is NULL');
            $sth->bindValue(':phone', $phone, PDO::PARAM_STR);
            $sth->execute();
            error_log("getGsmDeviceFromNumber Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
            return $sth->fetch();
        }
    }
    public function getGsmDeviceFromUuid($uuid){
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from gsm_device where uuid=:uuid and removed is NULL');
            $sth->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetch();
        }
    }
    public function getGsmDevice($user_id){
        $stmt = array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from gsm_device where rentee=:user_id and removed is NULL');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            while($obj =  $sth->fetch()){
                $stmt[]=$obj;
            }
            error_log("getSmsLog Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    public function getOneGsmDevice($user_id){
        $dev = null;
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from gsm_device where rentee=:user_id and removed is NULL order by sent_count asc limit 1');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            $dev = $sth->fetch();
            error_log("getOneGsmDevice Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $dev;
    }
    
    public function countGsmDeviceNotTaken($user_id){
        $count = null;
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select count(*) from gsm_device where rentee=0 and removed is NULL');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            $count = $sth->fetch()[0];
            error_log("countGsmDeviceNotTaken Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $count;
    }
    
    public function deleteGsmDevice($uuid, $id )
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('update gsm_device set removed=now() where uuid=:uuid');
            $sth->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $sth->execute();
            error_log("deleteGsmDevice Error=" . implode(",", $sth->errorInfo()).$uuid."--".$id);
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
    
    //////////////VIRTUAL NUMBER /////////////////
  
    
}