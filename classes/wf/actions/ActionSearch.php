<?php
require_once(__ROOT__ . '/classes/wf/actions/Action.php');

class ActionSearch extends Action {
    
    function __construct($uid, $bid, $number) {
        parent::__construct($uid, $bid, $number);
    }
    
    //NODE METHOD MATCHING REGEXLIST
    public function match_search($search, $sms){
        $this->log->trace("match_search ".$sms." from ".$search);
        $search = $this->to_char_string($search);
        $sms = $this->to_char_string($sms);
        $this->log->trace("--".$search."--in--".$sms."--");//." with ".print_r($intdef, true));
        if (preg_match('/'.$search.'/', $sms)){
            $this->log->debug("+++++".$search." ------- ".$sms);
            return array(True, "");
        }
        return array(False, "Match failed. ");
    }
    
    
    
}


?>