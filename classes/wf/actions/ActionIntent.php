<?php
require_once(__ROOT__ . '/classes/wf/actions/Action.php');
require_once(__ROOT__ . '/classes/sms/SmsIntent.php');

class ActionIntent extends Action {
    
    function __construct($uid, $bid, $number) {
        parent::__construct($uid, $bid, $number);
    }
    
    //NODE METHOD MATCHING REGEXLIST
    public function match_intent($intents, $sms){
        $sms = $this->to_char_string($sms);
        $intents = explode(",", $intents);
        foreach($intents as $intent){
            list($res, $mesg) = $this->_match_intent($intent, $sms);
            if (!$res)return array($res, $mesg);
        }
        return array(True, "");
    }
    
    //NODE METHOD MATCHING REGEXLIST
    private function _match_intent($intent, $sms){
        $sms = $this->to_char_string($sms);
        $int = new SmsIntent();
        $regexlist = $int->getIntentDefinitionN($intent);
        if ($regexlist == null){
            $this->log->trace("No regexlist found searching ".$intent);
            $patterns = array($intent);
        }
        else {
            $this->log->trace("match_intent ".$sms." from ".$intent." regexs=".$regexlist);
            $patterns = explode("\n",$regexlist);
        }
        foreach ($patterns as $pattern){
            $pattern = $this->to_char_string($pattern);
            if (strlen($pattern) < 2) continue;
            $this->log->trace("--".$pattern."--in--".$sms."--");//." with ".print_r($intdef, true));
            if (preg_match('/\b'.$pattern.'\b/', $sms)){
                $this->log->debug("+++++".$pattern." ------- ".$sms);
                return array(True, "");
            }
        }
        return array(False, "Match failed. ");
    }
    
    
    
}


?>