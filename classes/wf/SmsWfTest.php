<?php 
define ( '__ROOT__',  dirname(dirname(dirname ( __FILE__ ))));
error_log("Root=".__ROOT__);
require_once (__ROOT__ . '/classes/wf/SmsWfProcessor.php');
require_once (__ROOT__ . '/classes/core/Log.php');

$log = new Log("info");
//$bid="b02ebf86e4";
$bid="mlxiiu2wh6";
list ( $result, $resp ) = SmsWfUtils::processEmbeddedCommand ( "%%CLEARALL%%", 95, $bid, "919111111111" ); // special case where incoming SMS has command
$fp = fopen(__ROOT__."/data/chat1.txt", 'a');//opens file in append mode  
for($i=0; $i < 20; $i++){
    $a = readline('Enter SMS: ');
    fwrite($fp, $a."\n");
    $resp = SmsWfProcessor::processChat(95, $bid, "919111111111", $a);
    $log->info("SMS Resp>>>>>>>>>>> ".$resp, FILE_APPEND);
    fwrite($fp, "#".$resp."\n");
}
fclose($fp); 
?>