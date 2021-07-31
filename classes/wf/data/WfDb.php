<?php
require_once (__ROOT__ . '/classes/core/Sqlite.php');
require_once (__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/data/WfUserData.php');
require_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');


class WfDb extends Sqlite
{
    private $_number = null;
    private $_bid = null;
    private $dbUser=null;

    private static function substituteTemplate($field){
        $field = str_replace("#NL", "'<br/>'", $field);
        $field = str_replace("#LI", "'<li>'", $field);
        $field = str_replace("#IL", "'</li>'", $field);
        $field = str_replace("#SP", "'&emsp;'", $field);
        $field = str_replace("#H4", "'<h4>'", $field);
        $field = str_replace("#4H", "'</h4>'", $field);
        $field = str_replace("#IS", "'<img src=\"'", $field);
        $field = str_replace("#SI", "'\">'", $field);
        return $field;
    }

    // User id is the first param
    public function __construct($uid, $bid, $number)
    {
        parent::__construct($uid, $bid, self::$DB);
        $this->_bid=$bid;
        $this->_number=$number;
        $this->dbUser = new WfUserData($uid, $bid);
        $wfdb = new WfMasterDb();
        $format = $wfdb->getFormat($bid);
        if ($format & WfMasterDb::HTML || $format & WfMasterDb::MATH){
            $this->row_separator = self::ROW_HTML_SEPARATOR;
            $this->col_separator = self::COL_HTML_SEPARATOR;
        }
        else {
            $this->row_separator = self::ROW_TEXT_SEPARATOR;
            $this->col_separator = self::COL_TEXT_SEPARATOR;
        }
    }

    public function executeWfDb($sv){
        if (count($sv) < 4) {
            $this->log->error("Bad db template ".print_r($sv, true));
            $_SESSION['message']="Error: Bad db template ".print_r($sv, true);
            return array();
        }
        $dbtype = $sv[0];
        $tablename = trim($sv[1]);
        $field = trim($sv[2]);
        $condition = trim($sv[3]);
        $clause = trim($sv[4]);
        $sql=null;

        if (!$this->t_exists($tablename)){
            $this->log->error("Bad KB placeholder, KB table ".$tablename." does not exists");
            $_SESSION['message']="Error: Bad KB placeholder, KB table ".$tablename." does not exists";
            return array();
        }

        $field = self::substituteTemplate($field);

        if ($condition == ""){
            $sql = "select distinct ".$field." from ".$tablename.";";
        }
        else if (strpos($condition, "=:") === False){
            if ($this->_number == 911111111111){
                $sql = "select distinct ".$field." from ".$tablename." limit 1;";
            }
            else {
                $sql = "select distinct ".$field." from ".$tablename." where ".$condition." ".$clause.";";
            }
        }
        else {
            if ($this->_number == 911111111111){
                $sql = "select distinct ".$field." from ".$tablename." limit 1;";
            }
            else {
                $nv_dict = array();
                if (preg_match_all('/:([a-zA-Z0-9]+)/', $condition, $m)) {
                    $this->log->debug(SmsWfUtils::flatten($m[1]));
                    foreach($m[1] as $var_name){
                        $nv_dict[$var_name] = $this->dbUser->getUserData($this->_number, $var_name);
                        $this->log->debug($var_name."=".$nv_dict[$var_name]);
                        $condition = str_replace(":".$var_name, "'".$nv_dict[$var_name]."'", $condition);
                    }
                }
                $this->log->debug(SmsWfUtils::flatten($nv_dict));
                $sql = "select distinct ".$field." from ".$tablename." where ".$condition." ".$clause.";";
            }
        }
        try {
            if (strpos(",", $field) !== 0){ //multiple values per row
                $vals= $this->multiple_rows($sql);
                return $vals;
            }
            else { //single value list fields
                $vals = $this->value_list($sql);
                return $vals;
            }
        }catch (Exception $e){
            $this->log->error("Error: Query failed, ".$e);
            $_SESSION['message']="Error: Query failed, ".$e->getMessage();
            return array();
        }
    }

    public function save($name, $value){
        $this->dbUser->saveUserData($this->_number, $name, $value);
    }

    public function read($names){
        $this->dbUser->getUserData($this->_number, $name);
    }

    public static function deleteBotStore($user_id, $bot_id){
        $bot_kb_file = Sqlite::getBotKBFile($user_id, $bot_id);
        if (file_exists($bot_kb_file))unlink($bot_kb_file);
        $user_db_file = Sqlite::getUserDBFile($user_id, $bot_id);
        if (file_exists($user_db_file))unlink($user_db_file);
    }

    public static function copyBotKB($from_user_id, $from_bot_id, $to_user_id, $to_bot_id){
        $bot_kb_file = Sqlite::getBotKBFile($from_user_id, $from_bot_id);
        if (file_exists($bot_kb_file)){
            $to_db_file = Sqlite::getBotKBFile($to_user_id, $to_bot_id);
            error_log("Copying ".$bot_kb_file." to ". $to_db_file);
            copy($bot_kb_file, $to_db_file);
        }
    }

}


?>