<?php 
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/checks/Check.php');

class CheckNameAge extends Check {
    
    protected $_age_start = array("0","Minimum Age", "Minimum age that is allowed for an appointment", "Less than minimum age allowed");
    protected $_age_end = array("110", "Maximum Age",  "Maximum age that is allowed for an appointment","More than maximum age allowed");
    
    protected $_name_min = array("3", "Minimum Name Size", "Valid name minimum size", "Name size should be greater than 3 characters");
    protected $_name_max = array("20", "Maximum Name Size", "Valid name maximum size", "Name size exceeds the max length of 20 characters");
    
    function __construct(){
        parent::__construct();
    }
    
    
    public function range($value, $name){
        if ($name == "age"){
            return $this->_utils->range($value, "number", $this->_age_start, $this->_age_end);
        }
        else if ($name == "name"){
            return $this->_utils->range($value, "string", $this->_name_min, $this->_name_max);
        }
        else {
            throw new Exception("Unknow check type passed to CheckAppointment ".$type);
        }
    }
    
    
 }

?>