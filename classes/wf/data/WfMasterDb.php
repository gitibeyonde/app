<?php
// include the config
require_once (__ROOT__ . '/classes/sms/SmsIntent.php');
require_once (__ROOT__ . '/classes/core/Mysql.php');
require_once (__ROOT__ . '/classes/wf/def/SmsWfNode.php');

class WfMasterDb extends Mysql {

    //bit map for status field
    const TESTED=1;
    const SHARED=2;
    const HTML=4;
    const MATH=8;
    const EMAIL_OTP=16;
    const PHONE_OTP=32;


    public function __construct() {
        parent::__construct ();
    }

    public function saveWorkflow($user_id, $name, $category, $description, $format) {
        $description = $this->quote($description);
        $bot_id=self::guidv4();
        $this->log->trace("Botid generated=".$bot_id);
        $result = $this->changeRow ( sprintf ( "insert into sms_wf_master values( '%s', %d, '%s', NULL, '%s', '%s', %s,'%s', %d);",
                            $bot_id, $user_id, $name, $category, "", $description, "light",  $format) );
        error_log("saveWorkflow result=".$result);
        //also add start node
        if ($result == true){
            error_log("saveWorkflow saving node");
            $this->saveNode($bot_id, "start", 'Node Message', 'action=unmatched&next_state=start', "");
            return $bot_id;
        }
        else {
            return null;
        }
    }
    public function updateWorkflow($user_id, $bot_id, $name, $category, $description) {
        $description = $this->quote($description);
        $this->changeRow ( sprintf ( "update sms_wf_master set name='%s', category='%s', description=%s where bot_id='%s' and user_id=%d",
                $name, $category, $description,  $bot_id, $user_id) );
    }
    public function updateCss($user_id, $bot_id, $css) {
        $this->changeRow ( sprintf ( "update sms_wf_master set css='%s' where bot_id='%s' and user_id=%d",
                $css,  $bot_id, $user_id) );
    }
    public function saveNodeMessage($bot_id, $state, $message, $help) {
        $bot_id = $this->quote($bot_id);
        $state = $this->quote($state);
        $message = $this->quote($message);
        $help = $this->quote($help);
        $this->changeRow ( sprintf ( "insert into sms_wf_node(bot_id, state, message, help, changedOn) values( %s, %s, %s, %s, now()) ".
                "on duplicate key update message=%s, help=%s"
                , $bot_id, $state, $message, $help, $message, $help ) );
    }
    public function saveNode($bot_id, $state, $message, $actions, $help) {
        $bot_id = $this->quote($bot_id);
        $state = $this->quote($state);
        $message = $this->quote($message);
        $actions = $this->quote($actions);
        $help = $this->quote($help);
        $this->changeRow ( sprintf ( "insert into sms_wf_node values( %s, %s, %s, %s, %s, now()) ".
              "on duplicate key update message=%s, actions=%s, help=%s"
                , $bot_id, $state, $message, $actions, $help, $message, $actions, $help ) );
    }
    public function moveNodes($from_bot_id, $prefix, $to_bot_id){
        //get nodes
        $from_nodes = $this->getNodes($from_bot_id);
        foreach($from_nodes as $node){
            $actions = explode("\n", $node['actions']);
            $actions_array=array();
            foreach ($actions as $action){
                $this->log->trace("Action=".$action);
                $state_index = strripos($action, "=") + 1;
                $state = substr($action, $state_index);
                $this->log->trace("State_str=".$state);
                $state = $prefix."_".$state;
                $action = substr($action, 0, $state_index) . $state;
                $this->log->trace("Modified Action=".$action);
                $actions_array[]= $action;
            }
            $this->saveNode($to_bot_id, $prefix."_".$node['state'], $node['message'], implode("\n", $actions_array), $node['help']);
        }
    }

    public function copyWorkflow($from_bot_id, $to_user_id){
        $wf = $this->getWorkflow($from_bot_id);
        $owf = $this->getWorkflowWithName( $to_user_id, $wf['name']."-".substr(time()."", 2, 5));
        if ($owf != false){
            error_log("The workflow being copied already exists ".$owf["bot_id"]);
            return $owf["bot_id"];
        }
        $to_bot_id = $this->saveWorkflow($to_user_id, $wf['name']."-".substr(time()."", 2, 5), $wf['category'], $wf['description'],  $wf['status']);
        //get nodes
        $from_nodes = $this->getNodes($from_bot_id);
        foreach($from_nodes as $node){
            $this->saveNode($to_bot_id, $node['state'], str_replace($from_bot_id, $to_bot_id, $node['message']), $node['actions'], $node['help']);
        }
        $this->log->trace("Botid returned=".$to_bot_id);
        return $to_bot_id;
    }
    public function addTransition($bot_id, $node_state, $new_transition){
        $node=$this->getNode($bot_id, $node_state);
        $actions = $node['actions']. "\n". $new_transition;
        $this->log->trace("Action with transition=".$actions);
        $this->saveNode($bot_id, $node['state'], $node['message'], $actions, $node['help']);
    }
    public function isEmailOtp($bot_id) {
        return $this->selectRows( "select * from sms_wf_master where status & 16 = 16 and bot_id='%s'", $bid);
    }
    public function isPhoneOtp($bot_id) {
        return $this->selectRows( "select * from sms_wf_master where status & 32 = 32 and bot_id='%s'", $bid);
    }

    public function getStarNodes($bid) {
        return $this->selectRows ( sprintf ( 'select * from sms_wf_node where state like "\%%" and bot_id="%s"', $bid ) );
    }
    public function getNodes($bid) {
        return $this->selectRows ( sprintf ( 'select * from sms_wf_node where bot_id="%s"', $bid ) );
    }
    public function getAllNodes() {
        return $this->selectRows ('select * from sms_wf_node ');
    }
    public function getSmsWfNode($uid, $bid, $number, $state){
        $node_data = $this->getNode($bid, $state);
        return new SmsWfNode($uid, $bid, $number, $node_data['state'], $node_data['message'], explode("\n", $node_data['actions']), $node_data['help']);
    }
    public function getNode($bid, $state) {
        return $this->selectRow ( sprintf ( "select * from sms_wf_node where bot_id='%s' and state='%s'", $bid, $state ) );
    }
    public function deleteWorkflow($bot_id) {
        $this->changeRow ( sprintf ( "delete from sms_wf_master where bot_id='%s';",$bot_id) );
        $this->changeRow ( sprintf ( "delete from sms_wf_node where bot_id='%s';", $bot_id ) );
    }
    public function deleteNode($bot_id, $state) {
        $this->changeRow ( sprintf ( "delete from sms_wf_node where bot_id='%s' and state='%s';",
                $bot_id, $state ) );
    }
    public function getSharedWorkflow() {
        return $this->selectRows( "select * from sms_wf_master where status & 2 = 2"  );
    }
    public function getWorkflow($bot_id) {
        $bot_id = $this->quote($bot_id);
        return $this->selectRow( sprintf ( "select * from sms_wf_master where bot_id=%s;", $bot_id ) );
    }

    public function getWorkflowForUser($user_id, $bot_id) {
        $bot_id = $this->quote($bot_id);
        return $this->selectRow( sprintf ( "select * from sms_wf_master where bot_id=%s and user_id=%d;", $bot_id , $user_id) );
    }
    public function getWorkflowForNumber($phone) {
        return $this->selectRow( sprintf ( "select * from sms_wf_master where number_assigned=%d;", $phone ) );
    }
    public function getFormat($bot_id) {
        $bot_id = $this->quote($bot_id);
        return $this->selectOne( sprintf ("select status from sms_wf_master where bot_id=%s;", $bot_id ) );
    }
    public function setFormat($user_id, $bot_id, $format) {
        $this->changeRow ( sprintf ( "update sms_wf_master set status=%d where bot_id='%s' and user_id=%d",
                $format, $bot_id, $user_id) );
    }
    public function getWorkflows($user_id) {
        return $this->selectRows( sprintf ( "select * from sms_wf_master where user_id=%d;", $user_id ) );
    }
    public function getAllWorkflows() {
        return $this->selectRows( sprintf ( "select * from sms_wf_master") );
    }
    public function getWorkflowWithName($user_id, $bot_name) {
        $bot_name = $this->quote($bot_name);
        return $this->selectRow( sprintf ( 'select * from sms_wf_master where user_id=%d and name=%s', $user_id, $bot_name ) );
    }
    public function getUserChatbotForNumber($user_id, $number_assigned) {
        return $this->selectRow( sprintf ( 'select * from sms_wf_master where user_id=%d and number_assigned=%d', $user_id, $number_assigned ) );
    }

    public function detachNumber($user_id, $bot_id, $number) {
        $this->changeRow ( sprintf ( "update sms_wf_master set number_assigned=NULL where user_id=%d and bot_id='%s' and number_assigned=%d", $user_id, $bot_id, $number) );
    }

    public function attachNumber($user_id, $bot_id, $number) {
        $this->changeRow ( sprintf ( "update sms_wf_master set number_assigned='%d' where user_id=%d and bot_id='%s'", $number, $user_id, $bot_id) );
    }
}

?>