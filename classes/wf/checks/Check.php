<?php
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/checks/CheckParamsDb.php');
require_once (__ROOT__ . '/classes/core/Log.php');

class Check {
    
    private $bid=null;
    private $number=null;
    public $_utils=null;
    protected $_log= null;
    
    function __construct() { 
        $this->_utils=new SmsWfUtils();
        $this->_log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];;
    }
    
    public function _personalize($number, $bid){
        $this->number=$number;
        $this->bid=$bid; 
        
        $wfdb = new CheckParamsDb();
        foreach ( $this as $key => $value ) {
            if (strpos ( $key, "_" ) === 0) {
                $val = $wfdb->getBotParam($bot, "nd", $key);
                error_log("Key=".$key. ", value=". $val. ", def_value=".$value[0]);
                $value[0]= $val;
            }
        }
    }
    
    public function _fields() {
        $f = array ();
        foreach ( $this as $key => $value ) {
            if (strpos ( $key, "_" ) === 0) {
                $f [$key] = $value;
            }
        }
        return $f;
    }
    
    public function range($value, $type) {
        return "";
    }
    
}

?>