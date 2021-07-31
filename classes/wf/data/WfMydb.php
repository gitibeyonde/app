<?php
//define ( '__ROOT__',  dirname(dirname ( __FILE__ )));
require_once (__ROOT__ . '/classes/wf/data/WfUserData.php');
require_once (__ROOT__ . '/classes/core/Mysql.php');
require_once (__ROOT__ . '/classes/core/Log.php');

class WfMydb
{
    private $_number = null;
    private $_bid = null;
    private $_uid = null;
    private $dbUser=null;
    private $log=null;

    /**
     * Name value pair db for extracted values
     * @param Bot Id $bid
     * @param Phone number/session id $number
     */

    // User id is the first param
    public function __construct($uid, $bid, $number)
    {
        $this->log  = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        $this->_bid=$bid;
        $this->_uid=$uid;
        $this->_number=$number;
        $this->dbUser = new WfUserData($uid, $bid);
    }

    private static function substituteTemplate($field){
        $field = str_replace("#NL", "'<br/>'", $field);
        $field = str_replace("#LI", "'<li>'", $field);
        $field = str_replace("#IL", "'</li>'", $field);
        $field = str_replace("#SP", "'&emsp;'", $field);
        $field = str_replace("#H4", "'<h4>'", $field);
        $field = str_replace("#4H", "'</h4>'", $field);
        $field = str_replace("#IS", "'<img src='", $field);
        $field = str_replace("#SI", "'>'", $field);
        return $field;
    }

    public function execute($sv){
        if (count($sv) < 4) {
            throw new Exception("Bad db template ".print_r($sv, true));
        }
        $dbtype = $sv[0];
        $tablename = $sv[1];
        $field = $sv[2];
        $condition = $sv[3];
        $clause = $sv[4];
        $sql=null;

        if ($field == "-"){
            $this->log->debug("deleting ".$tablename." where ".$condition);
            $this->dbUser->deleteUserNameValue($this->_number, $condition);
            return array();
        }
        else if ($field == "+"){
            $this->log->debug("inserting name=".$condition.", value=".$clause);
            $this->dbUser->saveUserData($this->_number, $condition, $clause);
            return array();
        }
        else { //mydb/user_data/value/name/type
            $field = self::substituteTemplate($field);
            $this->log->debug("getting ".$condition." type=" . $clause." column=".$field);
            //Add if for sending test data
            if ($this->_number == 911111111111) {
                $value = $condition;
            }
            else {
                $value = $this->dbUser->getUserData($this->_number, $condition, $field);
            }
            return array(SmsWfUtils::format($value, $clause));
        }
    }


    public function save($name, $value){
        $this->dbUser->saveUserData($this->_number, $name, $value);
    }

}


?>