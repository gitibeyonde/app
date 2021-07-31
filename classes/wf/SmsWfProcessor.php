<?php
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/data/SmsContext.php');
require_once (__ROOT__ . '/classes/wf/def/WfWorkflow.php');
require_once (__ROOT__ . '/classes/sms/SmsMinify.php');
require_once (__ROOT__ . '/classes/core/Log.php');

class SmsWfProcessor {

    private static $_wf_registry = array(); //registry for workflows[bid]
    private static $_context = null;

    private static function getWorkflow($uid, $bid, $there_number){
        if ($bid == null) throw new Exception("Bot id cannot be null or non numeric ".$bid);
        if (!key_exists($bid, self::$_wf_registry)){
            self::$_wf_registry[$bid] = new WfWorkflow($uid, $bid, $there_number);
        }
        return self::$_wf_registry[$bid];
    }

    private static function process($user_id, $bot_id, $there_number, $state, $sms ){
        $log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        $log->debug("SMS Recvd<<<<<<<<<<<<< ".$sms. " in state =".$state);
        $wfdb = new WfMasterDb();

        $star_nodes = $wfdb->getStarNodes($bot_id);
        foreach($star_nodes as $star_node){
            $state = trim($star_node['state']);
            $star = new SmsWfNode($user_id, $bot_id, $there_number, $state, $star_node['message'], explode("\n", $star_node['actions']), explode("\n", $star_node['actions']), $star_node['help']);
            if ($star_state == "star_next"){
                $log->debug("Star node ".$star_state." Err=".$err. " msg=".$star->getMessage());
                $node = $this->_nodes_dictionary[$state];
                return array($node, SmsWfUtils::join ($star->getMessage()));
            }
        }

        $node_data = $wfdb->getNode($bot_id, $state);
        $node = new SmsWfNode($user_id, $bot_id, $there_number, $state, $node_data['message'], explode("\n", $node_data['actions']), $node_data['help']);
        list($new_state, $err) = $node->process($sms);
        $log->debug("State Change ". $new_state);
        $node_data = $wfdb->getNode($bot_id, $new_state);
        $state = trim($node_data['state']);
        $node = new SmsWfNode($user_id, $bot_id, $there_number, $state, $node_data['message'], explode("\n", $node_data['actions']), $node_data['help']);
        return array($node, $err);
    }

    private static function getContext($user_id, $bot_id){
        if (is_null(self::$_context)){
            self::$_context = new SmsContext($user_id, $bot_id);
        }
        return self::$_context;
    }

    public static function sendLink($user_id, $bot_id, $there_number) {
        $wfdb = new WfMasterDb();
        $wf = $wfdb->getWorkflow($bot_id);
        $minify = new SmsMinify();
        $url = $minify->createMicroAppUrlForUser($user_id, $bot_id, $there_number);

        return "Please, start ". $wf['name']." by clicking on the link -> ".$url;
    }


    public static function processChat($user_id, $bot_id, $there_number, $sms) {
        $log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];

        $state = null;
        list ( $context_str, $prev_sms ) = self::getContext($user_id, $bot_id)->getContext ($there_number );
        $log->debug ("Context = ".$context_str. " prev sms=".$prev_sms);
        if ($context_str == null) {
            $state = "start";
        } else {
            parse_str ( $context_str, $params );
            $state = $params ['state'];
            $prev_state = $params ['prev_state'];
        }

        $log->debug ( "Start State=" . $state );

        list ( $node, $err ) = self::process ( $user_id, $bot_id, $there_number, $state, $sms );
        $new_state = $node->getState ();

        $log->debug ( "New State=" . $new_state . " Prev State=".$state);

        self::getContext($user_id, $bot_id)->saveContext ($there_number, "state=" . $new_state . "&prev_state=" . $state, $sms );


        $resp = SmsWfUtils::join ( $node->getMessage () );

        return $err . $resp;
    }
}

?>


