<?php
require_once(__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once(__ROOT__ . '/classes/wf/data/WfDb.php');
require_once(__ROOT__ . '/classes/wf/data/WfMydb.php');

class Action {
    
    protected $_uid=null;
    protected $_bid=null;
    protected $_number=null;
    protected $_utils=null;
    protected $log= null;
    
    function __construct($uid, $bid, $number) { 
        $this->_number = $number;
        $this->_uid = $uid;
        $this->_bid = $bid;
        $this->_utils = new SmsWfUtils();
        $this->log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];;
    }
    
    //"i am the START {{db/sms_cat_healthcare_doctor/drname/speciality='CARDIOLOGY'/}} this is in middle, pattern={{ex/name/string/len(3-10)/}}, it ends with me"
    public function parse_pattern_string($pattern_string){
        $pr = array();
        $fields=array();
        $this->log->trace("parse_pattern_string string=".$pattern_string);
        if (preg_match_all("/(.*?)\{\{(.+?)\}\}(.*?)$/s", $pattern_string, $m)) {
            $this->log->trace("parse_pattern_string string 1=".SmsWfUtils::flatten($m));
            $pr[]=$m[1][0];
            $fields[]=explode("/", $m[2][0]);
            $this->log->trace("parse_pattern_string string 2=".print_r($m[2][0], true));
            if (count($fields[0]) != 5){
                $this->log->error( "FATAL: Bad expression ".$m[2][0]. ", in ".$pattern_string ." Expression syntax is <op>/<f1>/<f2>/<f3>/");
                $_SESSION['message'] = "FATAL: Bad expression ".$m[2][0]. ", in ".$pattern_string ." Expression syntax is <op>/<f1>/<f2>/<f3>/";
                return $pr;
            }
            $pr[] = $fields[0];
            //check if suffix has another pattern
            $result = $this->parse_pattern_string($m[3][0]);
            if ($result != null && is_array($result)){
                foreach($result as $item){
                    $pr[]=$item;
                }
            }
            else {
                $pr[] = $m[3][0];
            }
            return $pr;
        }
        else {
            return $pattern_string;
        }
    }
    
    
    public function substitue_pattern($pattern_string){
        $pattern_array = $this->parse_pattern_string($pattern_string);
        
        if ($pattern_array == null){
            return array();
        }
        else if (!is_array($pattern_array)){
            return array($pattern_array);
        }
        $this->log->trace("substitue_pattern array 1=".SmsWfUtils::flatten($pattern_array));
        $ra=array();
        foreach($pattern_array as $item){
            $this->log->debug("substitue_pattern item 2=".SmsWfUtils::flatten($item));
            if (is_array($item) && $item[0] == "db"){
                $db = new WfDb($this->_uid, $this->_bid, $this->_number);
                $vals = $db->executeWfDb($item);
                $this->log->debug("VALS=".SmsWfUtils::flatten($vals));
                $mid=null;
                foreach($vals as $val){
                    $this->log->debug("VAL=".SmsWfUtils::flatten($val));
                    if ($mid == null){
                        $mid = SmsWfUtils::put_together($val, $db->col_seprator, "", $db->row_separator);
                    }
                    else {
                        $mid = $mid.SmsWfUtils::put_together($val, $db->col_seprator, "", $db->row_separator);
                    }
                }
                $ra[] = $mid;
                $this->log->debug("substitue_pattern substituted 3=".$mid);
            }
            else if (is_array($item) && $item[0] == "mydb"){
                $db = new WfMydb( $this->_uid, $this->_bid, $this->_number);
                $vals = $db->execute($item);
                $mid=null;
                foreach($vals as $val){
                    if ($mid == null){
                        $mid = $val;
                    }
                    else {
                        $mid = $mid.", ".$val;
                    }
                }
                $ra[] = $mid;
                $this->log->debug("substitue_pattern substituted 4=".SmsWfUtils::flatten($mid));
            }
            else if (is_array($item) && $item[0] == "dblookup"){
                    $table = $item[1];
                    $tag_name = $item[2];
                    $info_column = $item[3];
                    $db = new WfUserData($this->_uid, $this->_bid);
                    $tag_value = $db->getUserData($this->_number, $tag_name);
                    $this->log->debug("VALS=".$tag_value);
                    if ($table != null && $tag_value != null){
                        $ra[] = $this->fuzzySearch($table, $tag_value);
                    }   
            }
            else if (is_array($item)){
                $this->log->error("ERROR: The bad first token in pattern, it should be one of db, mydb, dblookup".$item[0]);
                $_SESSION['message'] = "ERROR: The bad first token in pattern, it should be one of db, mydb, dblookup and not ".$item[0];
                return array();
            }
            else {
                $ra[] = $item;
            }
        }
        $this->log->debug("substitue_pattern joined 5=".SmsWfUtils::join($ra));
        return $ra;
    }
    
    // NODE METHOD TO EXTRACT MATCH
    public function extract_match($pattern_string, $sms){
        if ($pattern_string == null || $pattern_string == ""){
            throw new Exception("No pttern string given while trying to extract");
        }
        
        $pattern_array = $this->parse_pattern_string($pattern_string);
        
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
            $check_result ="";
            foreach($ra as $r){
                $this->log->trace("extract_match r= ".SmsWfUtils::flatten($r)." from ".SmsWfUtils::flatten($m[$count]));
                $name = $r[1];
                $type = $r[2];
                if ($type == "fixed"){ // No extraction from SMS rather a fixed value
                    $value = $r[3];
                }
                else {
                    $validate = $r[3];
                    $this->log->trace("extract_match name=".$m[$count][0]." type=".$type." validate=".$validate);
                    $value = $this->_utils->fix_type_value($m[$count][0], $type, $validate);
                    if ($value == null){
                        return array(False, "Bad input. ");
                    }
                    else {
                        $this->log->trace("extract_match name=".$name.", value=".$value." type=".$type." valid=".$validate);
                        $check_result = CheckFactory::_check($this->_bid, $this->_number, $value, $name);
                        $this->log->trace("extract_match check result = ".$check_result);
                        $count = $count + 1;
                    }
                }
                $db = new WfMydb($this->_uid, $this->_bid, $this->_number);
                $db->save($name, $value);
                $error = $error.$check_result;
            }
        }
        else {
            return array(False, "Bad formatting. ");
        }
        if ($error == ""){
            return array(True, "");
        }
        else {
            error_log("ERROR ".$error);
            return array(False, $error);
        }
    }
    
    
    protected function fuzzySearch($table, $ptag){
        $sql = "select tags, info from ".$table;
        $db = new WfDb($this->_uid, $this->_bid, $this->_number);
        $rows = $db->multiple_rows_cols($sql);
        
        $simper = 0;
        $answer = null;
        foreach ($rows as $row){
            $tags = explode(",", $row['tags']);
            $simp =0;
            foreach($tags as $tag){
                similar_text($ptag, $tag, $sim);
                $simp += $sim;
            }
            if ($simp > $simper){
                $simper = $simp;
                $answer = $row['info'];
            }
        }
        $this->log->trace("Answer =".$answer);
        return $answer;
    }
    
    //remove superflous informtion for intent comparison
    protected function to_char_string($sms){
        $this->log->trace("Before ".$sms);
        $sms = preg_replace("/\r|\n/", "", $sms);
        $sms = preg_replace('/\s+/', ' ', $sms);
        $sms = preg_replace("/[^a-zA-Z0-9,.]/", "", $sms);
        $sms = strtolower($sms);
        $this->log->trace("After ".$sms);
        return $sms;
    }
   
}


?>