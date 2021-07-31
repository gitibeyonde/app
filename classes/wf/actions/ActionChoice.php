<?php
require_once(__ROOT__ . '/classes/wf/actions/Action.php');
require_once(__ROOT__ . '/classes/wf/checks/CheckFactory.php');
require_once(__ROOT__ . '/classes/wf/data/WfDb.php');
require_once(__ROOT__ . '/classes/wf/data/WfMydb.php');

class ActionChoice extends Action {
    
    function __construct($uid, $bid, $number) {
        parent::__construct($uid, $bid, $number);
    }
    
    public function extract_choice($choices_pattern, $choices_array, $extraction_pattern, $sms){
        $this->log->debug("Pattern=".print_r($choices_pattern, true)." Choice=".print_r($choices_array, true)." Sms=".$sms);
        $ext_array = explode(",",$extraction_pattern);
        $ext_array_count = count($ext_array);
        if ( $ext_array_count > 0){//there are multiple extractions
            $sms_array = explode(",", $sms);
            $sms_array_count = count($sms_array);
            if ($sms_array_count < $ext_array_count){
                return array(False, "Not enough values. ");
            }
        }
        //check if SMS contains multiple values
        if ($choices_pattern != null){
            $choice = $this->fix_choice_pattern($choices_pattern, $sms_array[0]);
        }
        else if ($choices_array != null){
            $choice = $this->fix_choice_array($choices_array, $sms);
        }
        $this->log->debug("process CHOICE=".$choice);
        if ($choice == null){
            return array(False, "Bad Choice. ");
        }
        else if ($extraction_pattern != null){
            $sms_array[0]=$choice;
            for($i=0;$i<$ext_array_count; $i++){
                list($bool, $res_str) = $this->extract_match($ext_array[$i],$sms_array[$i]);
                if (!$bool){
                    return array($bool, $res_str);
                }
            }
            return array(True, "");
        }
        else {
            throw new Exception("process the choice =".$choice." but extraction pattern is null for sms=".$sms);
        }
    }
    
    public function fix_choice_array($choices, $sms){
        $this->log->debug("fix_choice_array Choices=".SmsWfUtils::flatten($choices)." from sms=".$sms);
        $perc=0;
        $sms = $this->to_char_string($sms);
        $match = $choices[0];
        foreach ($choices as $str_v){
            $orig_strv = $str_v;
            $str_v = preg_replace('/\s+/', ' ', $str_v);
            $str_v = strtolower(preg_replace("/(?![.=$'€%-])\p{P}/u", "", $str_v));
            similar_text($str_v, $sms, $nperc);
            $this->log->debug("$nperc, $str_v, $sms");
            if ($nperc > $perc) {
                $match = $orig_strv;
                $perc = $nperc;
            }
            if ($nperc > 70){
                break;
            }
        }
        if ($nperc < 10) {
            return null;
        }
        $this->log->debug("fix_choice_array Choice=".$match);
        return $match;
    }
    // Fix choice from choice extraction pattern
    public function fix_choice_pattern($pattern_string, $sms){
        
        $pattern_array = $this->parse_pattern_string($pattern_string);
        
        $this->log->debug("fix_choice ".SmsWfUtils::flatten($pattern_array));
        $choices=array();
        foreach($pattern_array as $item){
            if (is_array($item)){
                if ($item[0] == "db"){
                    // uid is first param
                    $db = new WfDb($this->_uid, $this->_bid, $this->_number);
                    $vals = $db->executeWfDb($item);
                    $choices=array();
                    foreach($vals as $val){
                        $choices[] = SmsWfUtils::join($val);
                    }
                }
                else if ($item[0] == "mydb"){
                    // uid is first param
                    $db = new WfMydb($this->_uid, $this->_bid, $this->_number);
                    $choices = $db->execute($item);
                    $this->log->debug("Choice=".print_r($choices, true));
                }
                else {
                    throw new Exception("Unknow query type ".SmsWfUtils::flatten($item));
                }
            }
        }
        $this->log->debug("Choices=".SmsWfUtils::flatten($choices));
        $perc=0;
        $sms = $this->to_char_string($sms);
        foreach ($choices as $str_v){
            $orig_strv = $str_v;
            $str_v = preg_replace('/\s+/', ' ', $str_v);
            $str_v = strtolower(preg_replace("/(?![.=$'€%-])\p{P}/u", "", $str_v));
            similar_text($str_v, $sms, $nperc);
            $this->log->trace("$nperc, $str_v, $sms");
            if ($nperc > $perc) {
                $match = $orig_strv;
                $perc = $nperc;
            }
        }
        if ($perc > 50){
            $this->log->info("Choice=".$match);
            return $match;
        }
        else {
            return null;
        }
    }
    
    // NODE METHOD TO EXTRACT MATCH
    public function extract_match($pattern_string, $sms){
        
        $pattern_array = $this->parse_pattern_string($pattern_string);
        
        if ($pattern_array == null){
            throw new Exception("extract_match pattern_array is null");
        }
        $this->log->debug("extract_match pattern= ". SmsWfUtils::flatten($pattern_array)." sms=".$sms);
        $ra=array();
        $np=null;
        $error="";
        foreach($pattern_array as $item){
            if (is_array($item)){
                $np = $np.'(.*?)';
                $ra[] = $item;
            }
            else {
                $np = $np.$item;
            }
        }
        if (preg_match_all('/'.$np.'$/', $sms, $m)) {
            $count = 1;
            foreach($ra as $r){
                $this->log->trace("extract_match r= ".SmsWfUtils::flatten($r)." from ".SmsWfUtils::flatten($m[$count]));
                $name = $r[1];
                $type = $r[2];
                $validate = $r[3];
                $value = $this->_utils->fix_type_value($m[$count][0], $type, $validate);
                $this->log->debug("extract_match name=".$name.", value=".$value." type=".$type." valid=".$validate);
                $check_result = CheckFactory::_check($this->_bid, $this->_number, $value, $name);
                $this->log->trace("extract_match check result = ".$check_result);
                $count = $count + 1;
                // uid is first param
                $db = new WfMydb($this->_uid, $this->_bid, $this->_number);
                $db->save($name, $value);
                $error = $error.$check_result;
            }
        }
        else {
            return array(False, "Match failed.");
        }
        if ($error == ""){
            return array(True, "");
        }
        else {
            $this->log->error("ERROR ".$error);
            return array(False, $error);
        }
    }
}


?>