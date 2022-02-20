<?php

class DeviceContext
{
    public $uuid = null;
    public $name = null;
    public $value = null;
    public $updated = null;
    
    private $db_connection            = null;
    public  $errors                   = array();
    public  $messages                 = array();

    public function __construct()
    {
    }

    private function databaseConnection()
    {
        // connection already opened
        if ($this->db_connection != null) {
            return true;
        } else {
            // create a database connection, using the constants from config/config.php
            try {
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR;
                return false;
            }
        }
    }
   

    public function getDeviceContext($uuid, $name)
    {
        $context=null;
        // if database connection opened
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $stmt = $this->db_connection->prepare('SELECT value FROM device_context WHERE uuid = :uuid and name = :name');
            $stmt->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $stmt->bindValue ( ':name', $name, PDO::PARAM_STR );
            $stmt->execute();
            $context = $stmt->fetch()[0];
            #error_log("getDeviceContext Error=".implode(",", $stmt->errorInfo()));
            #error_log("Value=". $context);
        }
        return $context;
    }
    
    public function updateDeviceContext($uuid, $name, $value) {
        if ($this->databaseConnection()) {
            $stmt_str="insert into device_context ( uuid, name, value, updated) values ('".$uuid."' , '".$name."' ,'".$value."' ,now()) on duplicate key update updated = now(), value ='". $value."'" ;
            #error_log($stmt_str);
            $stmt = $this->db_connection->prepare ($stmt_str);
            #error_log("updateLastAlert Error=".implode(",", $stmt->errorInfo()));
            $stmt->execute ();
        }
    }
    

    public function deleteContext($uuid, $name)
    {
        #error_log("Deleteing context =".$uuid);
        if ($this->databaseConnection()) {
            $stmt = $this->db_connection->prepare ( 'delete FROM device_context WHERE uuid = :uuid and name = :name' );
            $stmt->bindValue ( ':uuid', $uuid, PDO::PARAM_STR );
            $stmt->bindValue ( ':name', $name, PDO::PARAM_STR );
            $stmt->execute ();
            #error_log("Error=".implode(",", $stmt->errorInfo()));
        }
    }
     
}

?>
