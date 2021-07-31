<?php
require_once(__ROOT__ . '/classes/wf/actions/Action.php');

class ActionUnmatched extends Action {
    
    function __construct($uid, $bid, $number) {
        parent::__construct($uid, $bid, $number);
    }
    
    //NODE METHOD MATCHING REGEXLIST
    public function match_unmatched($intent, $sms){
        $this->log->debug("Catching all !");
        return array(True, "");
    }
    
    
    
}


?>