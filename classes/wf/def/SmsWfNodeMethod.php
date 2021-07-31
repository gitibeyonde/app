<?php
//define ( '__ROOT__',  dirname(dirname ( __FILE__ )));
require_once(__ROOT__ . '/classes/wf/actions/ActionFactory.php');

class SmsWfNodeMethod
{

    private $db_connection = null;

    private $_action = null; //"intent","choices", "mchoices","extract", "search"
    private $_extract = null;
    private $_choices_array = null;
    private $_choices_extract = null;
    private $_intent = null;
    private $_search = null;
    private $_upload = null;
    private $_next_state = null;
    private $_sms = null;
    private $_acts = null;
    private $_log = null;

    private $utils=null;


    //action=extract&pattern={{name/string/len(3-10)}},{{age/number/validate(0-110)}})&next_state=next_state
    //action=choices&choices={{db/doctor/city/speciality=:speciality/}}&extract={{ex/city/string/text/}}&next_state=branch
    //match(illness_dermatologist)=next_state
    public function __construct($uid, $bid, $number, $action_str, $utils)
    {
        $this->_log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        parse_str($action_str,$params);
        $this->utils =$utils;
        if ( !isset($params["action"]) || $params["action"] == null){
            throw new Exception("The node has no action ".$action_str);
        }
        else  if ($params["next_state"] == null){
            throw new Exception("The node has no next state ");
        }
        $this->_action = $params["action"];
        $this->_acts = ActionFactory::_get($uid, $bid, $number, $this->_action);
        if ( $this->_action == "extract"){
            $this->_extract = $params["extract"];
        }
        else if ($this->_action == "choices"){
            //error_log("Param Choice =".$params["choices"]);
            if (strpos($params["choices"], "{{") === false) {
                if (strpos ( $params["choices"], "," ) === false) {//single value choice
                    $this->_choices_array = array($params["choices"]);
                }
                else {//For comma separated list
                    $this->_choices_array=explode(",", $params["choices"]);
                }
            }
            else {
                $this->_choices_extract=$params["choices"];
            }
            $this->_extract = $params["extract"];
        }
        else if ( $this->_action == "intent"){
            $this->_intent = $params["intent"];
            if (isset($params["extract"])){
                $this->_extract = $params["extract"];
            }
        }
        else if ( $this->_action == "search"){
            $this->_search = $params["search"];
            if (isset($params["extract"])){
                $this->_extract = $params["extract"];
            }
        }
        else if ( $this->_action == "upload"){
            $this->_upload = $params["upload"];
        }
        else if ( $this->_action == "unmatched"){
            //do nothing just move to next state
        }
        else if ( $this->_action == "none"){
        }
        else {
            throw new Exception("Unknown action ".$action);
        }
        $this->_next_state = trim($params["next_state"]);
    }

    public function _compile(){

    }

    public function process($sms){
        $this->_log->debug("Method: input is: ".$sms);
        if ($this->_action == "extract"){
            return $this->_acts->extract_match($this->_extract, $sms);
        }
        else if ($this->_action == "choices"){
            return $this->_acts->extract_choice($this->_choices_extract, $this->_choices_array, $this->_extract, $sms);
        }
        else if ($this->_action == "intent"){
            list($bool, $intent) = $this->_acts->match_intent($this->_intent, $sms);
            if ($bool && $this->_extract != null){
                return $this->_acts->extract_match($this->_extract, $sms);
            }
            else {
                return array($bool, $intent);
            }
        }
        else if ($this->_action == "search"){
            list($bool, $intent) = $this->_acts->match_search($this->_search, $sms);
            if ($bool && $this->_extract != null){
                return $this->_acts->extract_match($this->_extract, $sms);
            }
            else {
                return array($bool, $intent);
            }
        }
        else if ($this->_action == "upload"){
            return array(True, "");
        }
        else if ($this->_action == "unmatched"){
            return $this->_acts->match_unmatched(null, $sms);
        }
        else if ($this->_action == "none"){
            return array(True, "");
        }
        else {
            throw new Exception("Unknown Action =".$this->_action);
        }
    }

    public function getNextState(){
        return $this->_next_state;
    }

    public function toString(){
        $str = "Action=".SmsWfUtils::flatten($this->_action);
        if ($this->_action == "extract"){
            $str = $str . ", Pattern=".SmsWfUtils::flatten($this->_extract);
        }
        else if ($this->_action == "choices"){
            $str = $str . ", Choice Array=".SmsWfUtils::flatten($this->_choices_array).
            ", Choice Extracted List=".SmsWfUtils::flatten($this->_choices_extract) .
            ", Extractions=".SmsWfUtils::flatten($this->_extract);
        }
        else if ($this->_action == "intent"){
            $str = $str .", Intent=".SmsWfUtils::flatten($this->_intent).", Next State=".$this->_next_state;
        }
        else if ($this->_action == "search"){
            $str = $str .", Search=".SmsWfUtils::flatten($this->_search).", Next State=".$this->_next_state;
        }
        else if ($this->_action == "upload"){
            $str = $str .", Upload=".SmsWfUtils::flatten($this->_upload).", Next State=".$this->_next_state;
        }
        return "[".$str."]";
    }
}


?>