<?php 

class CheckParamsDb {
    
    private $db_connection = null;
    
    function __construct(){
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
    
    
    
    public function saveBotParam($bot_id, $category, $name, $value){
        if ($bot_id == null){
            throw new Exception("Bot id cannot be null");
        }
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'insert into sms_bot_params(bot_id, category, name, value, changedOn) ' .
                    'values(:bot_id, :category, :name, :value, now()) ' .
                    'ON DUPLICATE KEY UPDATE value = :value;' );
            $sth->bindValue ( ':bot_id', $bot_id, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->bindValue ( ':name', $name, PDO::PARAM_STR );
            $sth->bindValue ( ':value', $value, PDO::PARAM_STR );
            $sth->execute ();
        }
    }
    
    
    public function getBotParam($bot_id, $category, $name)
    {
        if ($bot_id == null){
            throw new Exception("Bot id cannot be null");
        }
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select value from sms_bot_params where bot_id=:bot_id and category=:category and name=:name');
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->bindValue(':category', $category, PDO::PARAM_STR);
            $sth->bindValue(':name', $name, PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetch()[0];
        }
    }
    
    public function saveBotError($bot_id, $category, $name, $value){
        if ($bot_id == null){
            throw new Exception("Bot id cannot be null");
        }
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'insert into sms_bot_errors(bot_id, category, name, value, changedOn) ' .
                    'values(:bot_id, :category, :name, :value, now()) ' .
                    'ON DUPLICATE KEY UPDATE value = :value;' );
            $sth->bindValue ( ':bot_id', $bot_id, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->bindValue ( ':name', $name, PDO::PARAM_STR );
            $sth->bindValue ( ':value', $value, PDO::PARAM_STR );
            $sth->execute ();
        }
    }
    
    
    public function getBotError($bot_id, $category, $name)
    {
        if ($bot_id == null){
            throw new Exception("Bot id cannot be null");
        }
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select value from sms_bot_errors where bot_id=:bot_id and category=:category and name=:name');
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->bindValue(':category', $category, PDO::PARAM_STR);
            $sth->bindValue(':name', $name, PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetch()[0];
        }
    }
}

?>