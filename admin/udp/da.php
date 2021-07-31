<?php
define ( '__ROOT__', dirname ( dirname ( dirname ( __FILE__ ))));
//error_log(__ROOT__);
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/NetUtils.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/classes/Sip.php');
require_once (__ROOT__ . '/classes/RegistryPort.php');

$message = '';
if ($_SERVER ['REQUEST_METHOD'] == "GET" && isset ( $_GET ['action'] )) {
    $action = $_GET ['action'];
    $uuid = $_GET ['uuid'];
    $username = $_GET['user_name'];
    if( $action == "Remove"){
        error_log("Remove action not accepted");
        exit;
    }
    $pr = new RegistryPort();
    list($ip, $port) = $pr->getIpAndPort($uuid);
    $aws = new Aws ();
    $client = new NetUtils ($username, $uuid, $port );
    $utils = new Utils ();
    
    $client->register();
    $td = $client->getTimeDeltaForLastPing();
    error_log("TimeDelta= ".$td);
    if ($td > 100){
        error_log( "The device is not online ");
        exit;
    }
    else {
        error_log( "The device is online and action is  ". $action);
    }
    foreach ( $utils->action_message as $action_name => $description ) {
        if ($action_name == $action) {
           if ($action == "Snap") {
                $client->sendActionBroker ( $uuid, $action, 'super' );
                error_log(   "Action " . $description . " is accepted, and is send to your device. It will take sometime for it to be effective !");
                break;
            } else {
               $client->sendActionBroker ( $uuid, $action, '' );
                error_log(  "Action " . $description . " is accepted, and is send to your device. It will take sometime for it to be effective !");
                break;
            }
        }
        if ($action == "Debug") {
            $data = $_GET['data'];
            error_log ( "Command=" . $data );
            $data = json_encode ($data);
            $client->sendActionBroker ( $uuid, $action, $data );
            break;
        }
    }
    
}

?>
