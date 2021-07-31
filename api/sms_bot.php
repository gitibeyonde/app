<?php

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config/config.php');
require_once (__ROOT__ . '/classes/wf/SmsWfProcessor.php');
require_once(__ROOT__.'/classes/wf/data/WfMasterDb.php');
require_once(__ROOT__.'/classes/device/GsmDevice.php');
require_once(__ROOT__.'/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/core/Log.php');

$log = $_SESSION['log'] = new Log ("info");
if (isset($_GET["uuid"])  &&  isset($_POST['t']) && isset($_GET["type"])){
    $uuid=urldecode($_GET["uuid"]);
    $token=urldecode($_POST["t"]);
    
    $utils = new Utils();
    
    $tok_code=$utils->checkToken($token, $uuid);
    if ( $tok_code != 0){//check token
        echo json_encode(array('errno' => 'token_'.$tok_code, 'msg' => 'Bad token'));
        error_log($uuid. 'Bad token'. $tok_code);
        die;
    }
    else {
        $type=urldecode($_GET["type"]);
        
        if ($type == "response"){
            $utils = new SmsUtils();
            $my_phone = urldecode($_GET["my_phone"]);
            $there_phone = urldecode($_GET["there_phone"]);
            $sms = urldecode($_POST["sms"]);
            
            $BDB = new WfMasterDb();
            if (strpos($sms, SmsUtils::bot_url_request_prefix) !== false){ // its a link sending request
                $bot_id = trim(str_replace(SmsUtils::bot_url_request_prefix, "", $sms));
                $bot = $BDB->getWorkflow($bot_id);
                $response = SmsWfProcessor::sendLink($bot['user_id'], $bot['bot_id'], $there_phone);
                $log->debug("response=".$response);
                echo json_encode($response);
            }
            else {
                $dev = new GsmDevice();
                $user_id = $dev->getGsmDeviceFromUuid($uuid)['rentee'];
                $log->debug($uuid." = ".$user_id. "==".$my_phone);
                if ($user_id == null){
                    $log->debug($uuid."Gsm Not configured");
                    echo json_encode("");
                }
                else {
                    $bot = $BDB->getUserChatbotForNumber($user_id, $my_phone);
                    if ($bot != null){
                        $response = SmsWfProcessor::processChat($user_id, $bot['bot_id'], $there_phone, $sms);
                        echo json_encode($response);
                    }
                };
            }
        }
        else {
            error_log($uuid."Unknown Op");
            echo json_encode("");
        }
    }
}
else {
    echo json_encode(array('errno' => 'param_402', 'msg' => 'Bad Params'));
}

?>

