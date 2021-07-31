<?php
define ( '__ROOT__', dirname ( dirname ( __FILE__ ) ) );
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/AwsSqs.php');
require_once (__ROOT__ . '/classes/Utils.php');

$message = '';
if ($_SERVER ['REQUEST_METHOD'] == "GET" && isset ( $_GET ['action'] )) {
    $action = $_GET ['action'];
    $uuid = $_GET ['uuid'];
    $device_name = $_GET ['device_name'];
    $user_name = $_GET ['user_name'];
    $user_id = $_GET ['user_id'];
    $user_phone = $_GET ['phone'];
    $view = $_GET ['view'];
    $server = $_GET ['server'];
    $token = $_GET ['tk'];
    $role = $_GET ['role'];
    
    $awssqs = new AwsSqs ();
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
    
    $loc = $_GET ['loc'];
    $timezone = $_GET ['timezone'];
    $local = $_GET ['local'];
    $box = $_GET ['box'];
    
    $tab = "";
    if (isset($_GET ['tab'])){
        $tab = $_GET ['tab'];
    }
    $urlpreffix = "Location: https://" . $server . "/index.php?";
    $urlsuffix =  "&uuid=" . $uuid . "&device_name=" . $device_name . "&tk=" . $token . 
    "&local=" . $local . "&loc=" . $loc . "&box=" . $box . "&timezone=" . $timezone . 
    "&user_name=" . $user_name . "&user_id=" . $user_id .  ($tab != "" ? "&tab=" . $tab : "" ); 
    
   
    
    foreach ( $utils->action_message as $action_name => $description ) {
        if ($action_name == $action) {
            error_log ( "Action =" . $action . " uuid=" . $uuid );
            if ($action == "SmsPing") {
                $awssqs->sendPing($user_id, $uuid, $user_phone, $user_name.",".$role.",".$device_name);
                header ( $urlpreffix .
                        "view=" . SETTINGS_DASH .  "&message=" . $message .
                        $urlsuffix);
                break;
            } 
        }
    }
}

?>
