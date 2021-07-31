<?php
// include the config
require_once (__ROOT__ . '/config/config.php');

class SmsMessage
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
    
    public function getMessage($user_id, $message_id="")
    {
        $smst = array();
        if ($this->databaseConnection()) {
            if ($message_id == "") {
                // database query, getting all the info of the selected user
                $sth = $this->db_connection->prepare('select * from sms_message where user_id=:user_id and removed is NULL');
                $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
                $sth->execute();
                error_log("getMessage Error=" . implode(",", $sth->errorInfo()));
                if ( $sth->errorInfo()[0] != "0000"){
                    $_SESSION['message'] = print_r($sth->errorInfo(), true);
                }
                while($obj =  $sth->fetch()){
                    $smst[]=$obj;
                }
                return $smst;
            }
            else { // database query, getting all the info of the selected user
                $sth = $this->db_connection->prepare('select * from sms_message where user_id=:user_id and id=:message_id and removed is NULL');
                $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
                $sth->bindValue(':message_id', $message_id, PDO::PARAM_STR);
                $sth->execute();
                error_log("getMessage-id Error=" . implode(",", $sth->errorInfo()));
                if ( $sth->errorInfo()[0] != "0000"){
                    $_SESSION['message'] = print_r($sth->errorInfo(), true);
                }
                return $sth->fetch();
            }
        }
    }
    
    public function updateMessage($user_id, $message_id, $template)
    {
        if ($this->databaseConnection()) {
            $sth = $this->db_connection->prepare('update sms_message set template=:template where user_id=:user_id and id=:id' );
            $sth->bindValue(':id', $message_id, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':template', $template, PDO::PARAM_STR);
            $sth->execute();
            error_log("updateMessage Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
    public function createMessage($user_id, $name, $template, $response)
    {
        if ($this->databaseConnection()) {
            $sth = $this->db_connection->prepare('insert into sms_message(user_id, name, template, response, createdOn) '
                    .' values (:user_id, :name, :template, :response, now())');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':name', $name, PDO::PARAM_STR);
            $sth->bindValue(':template', $template, PDO::PARAM_STR);
            $sth->bindValue(':response', $response, PDO::PARAM_STR);
            $sth->execute();
            error_log("createMessage Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
    public function deleteMessage($user_id, $message_id)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('update sms_message set removed=now() where id=:id and user_id=:user_id');
            $sth->bindValue(':id', $message_id, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("deleteMessage Error=" . implode(",", $sth->errorInfo()).$message_id.'='. $user_id);
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
    
    
    ///////////////EMAIL/////////////////////////
    
    
    public function getEmailMessage($user_id, $message_id="")
    {
        $smst = array();
        if ($this->databaseConnection()) {
            if ($message_id == "") {
                // database query, getting all the info of the selected user
                $sth = $this->db_connection->prepare('select * from email_message where user_id=:user_id and removed is NULL');
                $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
                $sth->execute();
                error_log("getMessage Error=" . implode(",", $sth->errorInfo()));
                if ( $sth->errorInfo()[0] != "0000"){
                    $_SESSION['message'] = print_r($sth->errorInfo(), true);
                }
                while($obj =  $sth->fetch()){
                    $smst[]=$obj;
                }
                return $smst;
            }
            else { // database query, getting all the info of the selected user
                $sth = $this->db_connection->prepare('select * from email_message where user_id=:user_id and id=:message_id and removed is NULL');
                $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
                $sth->bindValue(':message_id', $message_id, PDO::PARAM_STR);
                $sth->execute();
                error_log("getMessage-id Error=" . implode(",", $sth->errorInfo()));
                if ( $sth->errorInfo()[0] != "0000"){
                    $_SESSION['message'] = print_r($sth->errorInfo(), true);
                }
                return $sth->fetch();
            }
        }
    }
    
    public function createEmailMessage($user_id, $name, $subject, $template, $response)
    {
        if ($this->databaseConnection()) {
            $sth = $this->db_connection->prepare('insert into email_message(user_id, name, subject, template, response, createdOn) '
                    .' values (:user_id, :name, :subject, :template, :response, now())');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':name', $name, PDO::PARAM_STR);
            $sth->bindValue(':subject', $subject, PDO::PARAM_STR);
            $sth->bindValue(':template', $template, PDO::PARAM_STR);
            $sth->bindValue(':response', $response, PDO::PARAM_STR);
            $sth->execute();
            error_log("createMessage Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
    public function deleteEmailMessage($user_id, $message_id)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('update email_message set removed=now() where id=:id and user_id=:user_id');
            $sth->bindValue(':id', $message_id, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("deleteMessage Error=" . implode(",", $sth->errorInfo()).$message_id.'='. $user_id);
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
}
?>