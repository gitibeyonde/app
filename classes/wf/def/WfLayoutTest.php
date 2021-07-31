<?php
define ( '__ROOT__',  dirname(dirname(dirname(dirname ( __FILE__ )))));
error_log("Root=".__ROOT__);
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/def/SmsWfNode.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/def/WfLayout.php');

$log = new Log("info");
$bot_id = "bdbc24deb2a";
$wfl = new WfLayout(95, $bot_id);
$wfl->assignCoordinates();

list($sub_graph, $edges, $min, $max, $fo) = $wfl->getSubsetFor("start_demo");

foreach($sub_graph as $nodes){
    $log->debug(SmsWfUtils::flatten($nodes->getState()));
    $log->debug(SmsWfUtils::flatten($nodes->getCoordinate()));
}

foreach($edges as $edge){
    $log->debug(SmsWfUtils::flatten($edge));
}

error_log("MIN=".SmsWfUtils::flatten($min));
error_log("MAX=".SmsWfUtils::flatten($max));


error_log("FO=".$fo);
?>