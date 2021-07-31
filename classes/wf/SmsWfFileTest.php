<?php 
define ( '__ROOT__',  dirname(dirname(dirname ( __FILE__ ))));
error_log("Root=".__ROOT__);
require_once (__ROOT__ . '/classes/wf/SmsWfProcessor.php');
require_once (__ROOT__ . '/classes/core/Log.php');

$log = new Log("info");

list ( $result, $resp ) = SmsWfUtils::processEmbeddedCommand ( "%%CLEARALL%%", "95", "mlxiiu2wh6", "919111111111" ); // special case where incoming SMS has command

//$a = readline('Enter Filename: ');
$f="hospital.txt";
$fp = fopen("/Users/aprateek/work/tmp/bot_data/test_data/".$f, 'r');//opens file in append mode  
while($a=fgets($fp)){
    //$resp = SmsWfProcessor::processChat(95, "bdbc24deb2a", "919111111111", $a); //medical appointment
    if (trim($a) == "XXXX")break;
    $resp = SmsWfProcessor::processChat(95, "mlxiiu2wh6", "919111111111", $a);
    $fresp=fgets($fp);  
    $log->info("SMS Resp>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ".$resp."---".$fresp."\n\n");
}
fclose($fp);  

?>
