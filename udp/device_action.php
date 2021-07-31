<?php
define ( '__ROOT__', dirname ( dirname ( __FILE__ ) ) );
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/NetUtils.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/classes/Sip.php');
require_once (__ROOT__ . '/classes/Mp4.php');
require_once (__ROOT__ . '/classes/Mjpeg.php');

$message = '';
if ($_SERVER ['REQUEST_METHOD'] == "GET" && isset ( $_GET ['action'] )) {
    $action = $_GET ['action'];
    $uuid = $_GET ['uuid'];
    $port = $_GET ['port'];
    $device_name = $_GET ['device_name'];
    $user_name = $_GET ['user_name'];
    $user_id = $_GET ['user_id'];
    $view = $_GET ['view'];
    $server = $_GET ['server'];
    $token = $_GET ['tk'];
    $role = $_GET ['role'];
    
    $aws = new Aws ();
    $client = new NetUtils ( $user_name, $uuid, $port );
    $utils = new Utils ();
    
    $check_token = $utils->checkToken ( $token, $uuid );
    if ($check_token != 0) {
        $message = "Unauthorized bad token !";
        header ( "Location: https://" . $server . "/index.php?view=" . $view . "&message=" . $message );
        return;
    }
    
    $check_uuid = $utils->validateDevice ( $user_name, $uuid );
    if ($check_uuid != $uuid) {
        $message = "Unauthorized bad ownership !";
        header ( "Location: https://" . $server . "/index.php?view=" . $view . "&message=" . $message );
        return;
    }
    
    $client->register ();
    $td = $client->getTimeDeltaForLastPing ();
    
    $loc = $_GET ['loc'];
    $timezone = $_GET ['timezone'];
    $local = $_GET ['local'];
    $box = $_GET ['box'];
    $quality = "";
    if (isset($_GET ['quality'])){
        $quality = $_GET ['quality'];
    }
    
    $tab = "";
    if (isset($_GET ['tab'])){
        $tab = $_GET ['tab'];
    }
    $urlpreffix = "Location: https://" . $server . "/index.php?";
    $urlsuffix = ($quality != "" ? "&quality=" . $quality : "" ). "&uuid=" . $uuid . "&device_name=" . $device_name . "&tk=" . $token . 
    "&local=" . $local . "&loc=" . $loc . "&box=" . $box . "&timezone=" . $timezone . 
    "&user_name=" . $user_name . "&user_id=" . $user_id .  ($tab != "" ? "&tab=" . $tab : "" ); 
    
    // error_log("TimeDelta= ".$td);
    if ($td > 60 && $action != "Remove") {
        $message = "The device is not online for $td seconds ! ";
        header ( $urlpreffix .
                "view=" . $view .  "&message=" . $message .
                $urlsuffix);
    }
    
    foreach ( $utils->action_message as $action_name => $description ) {
        if ($action_name == $action) {
            error_log ( "Action =" . $action . " uuid=" . $uuid );
            if ($action == "Remove") {
                $aws->deleteMotionData ( $uuid );
                $dev = new Device ();
                $dev->deleteDevice ( $uuid );
                $message = $message . "Device Remove is removed from your account !";
                $client->sendActionBroker ( $uuid, $action, '' );
                header ( $urlpreffix .
                        "view=" . LIVE_DASH .  "&message=" . $message .
                        $urlsuffix);
                break;
            } else if ($action == "Snap") {
                $client->sendActionBroker ( $uuid, $action, 'super' );
                $message =  $message . "Action " . $description . " is accepted, and is send to your device. It will take sometime for it to be effective !";
                header ( $urlpreffix .
                        "view=" . $view .  "&message=" . $message .
                        $urlsuffix);
                break;
            } else if ($action == "RecordToggle") {
                $video_mode = $_GET ['mode'];
                
                if ($video_mode) {
                    $isRecoding = Mp4::isRecording ( $uuid );
                    if ($isRecoding) {
                        Mp4::stopRecording ( $uuid, $timezone );
                        $message = "Recording stopped mp4 !";
                    } else {
                        Mp4::startRecording ( $uuid, $timezone );
                        $message = "Recording started mp4 !";
                    }
                } else {
                    $isRecoding = Mjpeg::isRecording ( $uuid );
                    if ($isRecoding) {
                        Mjpeg::stopRecording ( $uuid, $timezone );
                        $message = "Recording stopped mjpeg !";
                    } else {
                        Mjpeg::startRecording ( $uuid, $timezone );
                        $message = "Recording started mjpeg !";
                    }
                }
                error_log ( "Message = " . $message );
                break;
            } else if (in_array ( $action, [ 'EnFcDtct','DecMDelta','IncMDelta','IncTol','DecTol','EnGrd','DsGrd', 'IncMotionQ', 'DecMotionQ' , 'IncSnapQ', 'DecSnapQ' ] )) {
                if ($role != "SUBS" && $role != "ADMIN") {
                    $message =  $message . "Unauthorized Action: Available to subscribing accounts !";
                    header ( $urlpreffix .
                            "view=" . $view .  "&message=" . $message .
                            $urlsuffix);
                } else {
                    $client->sendActionBroker ( $uuid, $action, '' );
                    $message =  $message . "Action " . $description . " is accepted, and is send to your device. It will take sometime for it to be effective !";
                    header ( $urlpreffix .
                            "view=" . $view .  "&message=" . $message .
                            $urlsuffix);
                }
                break;
            }
            else  {
                $client->sendActionBroker ( $uuid, $action, '' );
                $message = $action ." sent to your device !";
                error_log( $urlpreffix .
                    "view=" . $view .  "&message=" . $message .
                    $urlsuffix);
                header ( $urlpreffix .
                        "view=" . $view .  "&message=" . $message .
                        $urlsuffix);
                break;
            }
        }
    }
    if ($action == "Zoom") {
        $local = $_GET ['local'];
        $loc = $_GET ['loc'];
        $box = $_GET ['box'];
        $timezone = $_GET ['timezone'];
        // error_log ( "Data=" . $_GET ['area'] );
        $client->sendActionBroker ( $uuid, $action, $_GET ['area'] );
        $message = "Action Zoom " . $_GET ['area'] . " is accepted, and is send to your device. It will take sometime for it to be effective !";
        header ( $urlpreffix .
                "view=" . $view .  "&message=" . $message . "&zoom=" . $_GET ['area'] . 
                $urlsuffix);
    } else if ($action == "Debug") {
        $data = $_GET ['data'];
        error_log ( "Command=" . $data );
        $data = json_encode ( $data );
        $client->sendActionBroker ( $uuid, $action, $data );
        $message =  $message . "Sent command to your device !";
        header ( $urlpreffix .
                "view=" . $view .  "&message=" . $message . 
                $urlsuffix);
    }  else if ($action == "InstrumentVoice") {
        $data = json_encode ( array ("sipno" => $_GET ['sipno'],"secret" => $_GET ['secret'] 
        ) );
        // error_log ( "Data=" . $data );
        $client->sendActionBroker ( $uuid, $action, $data );
        $message =  $message . "Sent voice instrument command to your device !";
        header ( $urlpreffix .
                "view=" . $view .  "&message=" . $message . 
                $urlsuffix);
    } else if ($action == "PubKey") {
        // $data = $_GET ['data'];
        // error_log ( "Data=" . $data );
        // $data_parts = str_split($data, 24);
        // for ($i=0; $i < count($data_parts); $i = $i +1){
        // error_log ( "Data=" . $data_parts[$i] );
        // $client->sendActionBroker ( $uuid, "PK".$i, $data_parts[$i] );
        // sleep(1);
        // }
        $client->sendActionBroker ( $uuid, $action, '' );
        $message =  $message ."Sending the public key to your device !";
        header ( $urlpreffix .
                "view=" . $view .  "&message=" . $message . 
                $urlsuffix);
    }
}

?>
