<?php
require_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
require_once (__ROOT__ . '/classes/wf/def/SmsWfNode.php');


class WfWorkflow {

    public static $WORKING = 1;
    public static $SHARABLE = 2;

    public $_nodes_dictionary = array();
    public $_star_nodes = array();
    public $_log= null;

    public function __construct($uid, $bid, $number)
    {
        $this->_log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        $wfdb = new WfMasterDb();
        $format = $wfdb->getFormat($bid);
        $nlist = $wfdb->getNodes($bid);
        foreach($nlist as $node_data){
            $state = trim($node_data['state']);
            $action_array = explode("\n", $node_data['actions']);
            if (strpos($state, '%') !== 0){
                $this->_nodes_dictionary[$state]  = new SmsWfNode($uid, $bid, $number, $state, $node_data['message'], $action_array, $node_data['help']);
             }
             else {
                 $this->_star_nodes[] = new SmsWfNode($uid, $bid, $number, $state, $node_data['message'], $action_array, $node_data['help']);
             }
        }
    }

    public function process($state, $sms){
        $this->_log->debug("SMS Recvd<<<<<<<<<<<<< ".$sms. " in state =".$state);
        foreach($this->_star_nodes as $star){
            list($star_state, $err) = $star->process($sms);
            if ($star_state == "star_next"){
                $this->_log->debug("Star node ".$star_state." Err=".$err. " msg=".$star->getMessage());
                $node = $this->_nodes_dictionary[$state];
                return array($node, SmsWfUtils::join ($star->getMessage()));
            }
        }
        $node = $this->_nodes_dictionary[$state];
        list($new_state, $err) = $node->process($sms);
        $this->_log->debug("State Change ". $new_state);
        return array($this->_nodes_dictionary[$new_state], $err);
    }


    public function compile(){
        $states = array();
        $next_states = array();
        foreach($this->_nodes_dictionary as $state=>$node){
            array_push($states, $state);
            $ns = $node->getNextStates();
            foreach($ns as $s){
                array_push($next_states, $s);
            }
        }
        // check all the next states exists
        foreach($next_states as $ns){
            $exists = false;
            if (!in_array($ns, $states)){
                throw new Exception("FATAL:  State ".$ns." does not exists ");
            }
        }
    }

    public function toString(){
        $str="";
        foreach($this->_nodes_dictionary as $node){
            $str .=  "--" . $node->toString();
        }
        return $str;
    }

}


?>