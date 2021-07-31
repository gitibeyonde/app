<?php
//define ( '__ROOT__',  dirname(dirname ( __FILE__ )));
require_once (__ROOT__ . '/classes/wf/def/SmsWfNodeMethod.php');
require_once (__ROOT__ . '/classes/wf/checks/Check.php');
require_once (__ROOT__ . '/classes/wf/actions/Action.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/utils/WfUtils.php');

class SmsWfNode
{

    private $db_connection = null;

    private $_state = "";
    private $_prev_state = "";
    private $_message = null;
    private $_action = array();
    private $_action_str = array();
    private $_help = null;
    private $utils = null;
    private $_act = null;
    private $_log = null;

    private $_x=null;
    private $_y=null;


    public function __construct($uid, $bid, $number, $state, $message, $actions_array, $help)
    {
        $this->_log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        $this->utils = new SmsWfUtils();
        $this->_state = trim($state);
        if(!is_array($actions_array)){
            throw new Exception("Bad parameter actions_array".SmsWfUtils::flatten($actions_array));
        }
        $this->_action_str = $actions_array;
        foreach ($actions_array as $action_str){
            $this->_log->trace("method_str=".$action_str);
            $this->_action[] = new SmsWfNodeMethod($uid, $bid, $number, $action_str, $this->utils);
        }
        $this->_act = new Action($uid, $bid, $number);
        $this->_message = $message;
        $this->_help = $help;
    }

    public function setCoordinate($x, $y){
        $this->_x = $x;
        $this->_y = $y;
    }

    public function getCoordinate(){
        return array($this->_x, $this->_y);
    }

    public function getAction(){
        return $this->_action;
    }
    public function getHelp(){
        return $this->_help;
    }
    public function getState(){
        return $this->_state;
    }
    public function getMessage(){
        $this->_log->debug("getMessage=".SmsWfUtils::join($this->_message));
        $sms = $this->_act->substitue_pattern($this->_message);
        return $sms == null ? "No data found":$sms;
    }
    public function getMethod(){
        return $this->_action;
    }

    public function process($sms){
        $this->_log->debug("Node is: ".$this->toString(). " input is: ".$sms);
        $error_message = "";
        foreach($this->_action as $method){
            list($result_bool, $error_message) = $method->process($sms);
            if ($result_bool){
                $this->_log->debug("Node State Change=".$method->getNextState());
                return array($method->getNextState(), $error_message);
            }
        }
        $this->_log->debug("Node No State Change=".$this->_state. " error=".$error_message);
        return array($this->_state, $error_message);
    }
    public function getNextStates(){
        $ns = array();
        foreach ( $this->_action as $method) {
            $s = $method->getNextState();
            if (! in_array($s, $ns)){
                array_push($ns, $s);
            }
        }
        return $ns;
    }

    public function toString() {
        $result= "State=".$this->_state;
        return "{ ".$result." }";
    }

    public function getDisplay($p, $i, $uid, $bid, $number, $wfr){
        $html='<form class="wf_form" action="/a/'.$wfr.'.php" method="post" enctype="multipart/form-data">';
        $html=$html.'<input type=hidden name=p value="'.$p.'">';
        $html=$html.'<input type=hidden name=i value="'.$i.'">';
        $html=$html.'<input type=hidden name=t value="'.$number.'">';
        $html=$html.'<input type=hidden class="form-control" name="state" value="'.$this->getState().'">';
        $html=WfUtils::getDisplay($uid, $bid, $number, $this->_action_str, $html);
        return $html;
    }

    public function getChatInputs($p, $i, $uid, $bid, $number, $wfr){
        $html='<form class="wf_form" action="/a/'.$wfr.'.php" method="post" enctype="multipart/form-data">';
        $html=$html.'<input type=hidden name=p value="'.$p.'">';
        $html=$html.'<input type=hidden name=i value="'.$i.'">';
        $html=$html.'<input type=hidden name=t value="'.$number.'">';
        $html=$html.'<input type=hidden class="form-control" name="state" value="'.$this->getState().'">';
        $html=WfUtils::getChatInputs($uid, $bid, $number, $this->_action_str, $html);
        return $html;
    }

}


?>