<?php
// include the config
require_once (__ROOT__ . '/config/config.php');

class SmsIntent {
    private $db_connection = null;
    
    public static $bot_intent_category=array("general", "company", "healthcare", "fortuneteller");
    
    public function __construct() {
    }
    private function databaseConnection() {
        // if connection already exists
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                $this->db_connection = new PDO ( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS );
                return true;
            } catch ( PDOException $e ) {
                error_log(print_r($e));
                $_SESSION ['message'] = MESSAGE_DATABASE_ERROR . $e->getMessage ();
            }
        }
        return false;
    }
    public function createIntentN($intent, $type, $definition) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'insert into sms_intent(intent, type, definition) ' .
                    'values(:intent, :type, :definition)' );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':type', $type, PDO::PARAM_STR );
            $sth->bindValue ( ':definition', $definition, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "createIntentN Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
        }
    }
    public function createWorkflowNode($intent, $category, $context, $priority) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'insert into sms_workflow(intent, category, context, priority) ' .
                    'values(:intent, :category, :context, :priority)' );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->bindValue ( ':context', $context, PDO::PARAM_STR );
            $sth->bindValue ( ':priority', $priority, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "createWorkflowNode Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
        }
    }
    public function createIntentDepricated($intent, $category, $type, $context, $definition, $priority) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'insert into sms_bot_intent(intent, category, type, context, definition, priority, changedOn) ' . 
                    'values(:intent, :category, :type, :context, :definition, :priority, now())' );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->bindValue ( ':type', $type, PDO::PARAM_STR );
            $sth->bindValue ( ':context', $context, PDO::PARAM_STR );
            $sth->bindValue ( ':definition', $definition, PDO::PARAM_STR );
            $sth->bindValue ( ':priority', $priority, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "createIntent Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
        }
    }
    public function changePriorityDepricated($intent, $category, $priority) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'update sms_bot_intent set priority=:priority where intent=:intent and category=:category' );
            $sth->bindValue ( ':priority', $priority, PDO::PARAM_STR );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "editIntent Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
        }
    }
    public function updateIntentN($intent, $definition) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'update sms_intent set definition=:definition where intent=:intent' );
            $sth->bindValue ( ':definition', $definition, PDO::PARAM_STR );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "editIntentN Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
        }
    }
    
    public function updateWorkflowNode($intent, $category, $context, $priority) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'update sms_workflow set priority=:priority, context=:context where intent=:intent and category=:category' );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->bindValue ( ':context', $context, PDO::PARAM_STR );
            $sth->bindValue ( ':priority', $priority, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "updateWorkflowNode Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
        }
    }
    
    public function editIntentDepricated($intent, $category, $definition, $context) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'update sms_bot_intent set definition=:definition, context=:context where intent=:intent and category=:category' );
            $sth->bindValue ( ':definition', $definition, PDO::PARAM_STR );
            $sth->bindValue ( ':context', $context, PDO::PARAM_STR );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "editIntent Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
        }
    }
    
    public function getWorkflowForCategory($intcat) {
        $intents = array ();
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'select * from  sms_workflow where category=:category order by priority' );
            $sth->bindValue ( ':category', $intcat, PDO::PARAM_STR );
            $sth->execute ();
            while ( $obj = $sth->fetch () ) {
                $intents [] = $obj;
            }
        }
        return $intents;
    }
    public function getIntentsForCategory($intcat) {
        $intents = array ();
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'select a.*, b.* from sms_intent a, sms_workflow b  where a.intent=b.intent and b.category=:category order by priority' );
            $sth->bindValue ( ':category', $intcat, PDO::PARAM_STR );
            $sth->execute ();
            while ( $obj = $sth->fetch () ) {
                $intents [] = $obj;
            }
        }
        return $intents;
    }
    public function getIntentsN() {
        $intents = array ();
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'select * from sms_intent');
            $sth->execute ();
            while ( $obj = $sth->fetch () ) {
                $intents [] = $obj;
            }
        }
        //error_log("Intents=".print_r($intents, true));
        return $intents;
    }
    public function getIntents($bot_id) {
        $intents = array ();
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            //$sth = $this->db_connection->prepare ( 'select a.* from sms_bot_intent a, sms_bot b where b.id=:bot_id and a.category=b.category order by a.priority' ); 
            $sth = $this->db_connection->prepare ( 'select b.*, c.* from sms_bot a, sms_intent b, sms_workflow c '
                    . ' where a.id=:bot_id and c.intent=b.intent and a.category=c.category order by c.priority;');
            $sth->bindValue ( ':bot_id', $bot_id, PDO::PARAM_STR );
            $sth->execute ();
            while ( $obj = $sth->fetch () ) {
                $intents [] = $obj;
            }
        }
        error_log("Intents=".print_r($intents, true));
        return $intents;
    }
    public function getIntentDefinitionN($intent) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'select definition from sms_intent where intent=:intent' );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->execute ();
            return $sth->fetch ()[0];
        }
    }
    public function getWorkflowNode($intent, $category) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'select * from sms_workflow where intent=:intent' );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->execute ();
            return $sth->fetch ()[0];
        }
    }
    public function getIntentDefinition($intent, $category) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'select definition from sms_intent where intent=:intent' );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->execute ();
            return $sth->fetch ()[0];
        }
    }
    public function deleteIntentN($intent) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'delete from sms_intent where intent=:intent' );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "deleteIntentN Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
            return $sth->fetch ();
        }
    }
    public function deleteWorkflowNode($intent, $category) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'delete from sms_workflow where intent=:intent and category=:category' );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "deleteWorkflowNode Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
            return $sth->fetch ();
        }
    }
    public function deleteIntentDepricated($intent, $category) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'delete from sms_bot_intent where intent=:intent and category=:category' );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "deleteIntent Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
            return $sth->fetch ();
        }
    }
    
    
    public function isIntentContextRequired($bot_id, $intent){
        $filters = $this->getIntents($bot_id);
        foreach($filters as $filter){
            if (strpos($filter['context'], $intent) !== false){
                error_log("CONTEXT required---");
                return true;
            }
        }
        error_log("CONTEXT NOT required---");
        return false;
    }
    
    ////////////////////USER INTENT ////////////////////
    
    public function saveUserIntent($bot_id, $intent, $response) {
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'insert into sms_user_intent_reponse(bot_id, intent, response, changedOn) ' . 
                    'values(:bot_id, :intent, :response, now()) ' .
                    'ON DUPLICATE KEY UPDATE response = :response;' );
            $sth->bindValue ( ':bot_id', $bot_id, PDO::PARAM_STR );
            $sth->bindValue ( ':intent', $intent, PDO::PARAM_STR );
            $sth->bindValue ( ':response', $response, PDO::PARAM_STR );
            $sth->execute ();
            error_log ( "saveUserIntent Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
        }
    }
    
    public function getResponses($bot_id)
    {
        $resps = array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select intent, response from sms_user_intent_reponse where bot_id=:bot_id');
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->execute();
            while ( $obj = $sth->fetch () ) {
                $resps [] = $obj;
            }
            error_log ( "getResponses Error=" . implode ( ",", $sth->errorInfo () ) );
            if ($sth->errorInfo () [0] != "0000") {
                $_SESSION ['message'] = print_r ( $sth->errorInfo (), true );
            }
        }
        return $resps;
    }
    
    public function getIntentResponse($bot_id, $intent)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select response from sms_user_intent_reponse where bot_id=:bot_id and intent=:intent');
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->bindValue(':intent', $intent, PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetch()[0];
        }
    }
    public function deleteIntentResponse($bot_id, $name)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('delete from sms_user_intent_reponse where bot_id=:bot_id and intent=:intent');
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->bindValue(':name', $name, PDO::PARAM_STR);
            $sth->execute();
            error_log("deleteChatbotNV Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
   
}