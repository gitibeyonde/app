<?php
define ( '__ROOT__', dirname ( __FILE__ ) );
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/AlertConfig.php');
require_once (__ROOT__ . '/classes/AlertRaised.php');
require_once (__ROOT__ . '/classes/Face.php');
require_once (__ROOT__ . '/classes/Aws.php');
session_start ();
$message = '';
if ($_SERVER ['REQUEST_METHOD'] == "GET" && isset ( $_GET ['action'] )) {
    $action = $_GET ['action'];
    $user_name = $_SESSION ['user_name'];
    $user_email = $_SESSION ['user_email'];
    $view = $_SESSION ['view'];
    if (isset($_GET['view'])){
        $view = $_GET ['view'];
    }
    if (isset($_GET['tab'])){
        $tab = $_GET ['tab'];
    }
    else {
        $tab = "device";
    }
    $user = UserFactory::getUser ( $user_name, $user_email );
    $utils = new Utils ();
    
    if ($action == "CreateBox") {
        $box = $_GET ["box"];
        $user->createBox ( $user_name, $box );
        $uuid = $_GET ['uuid'];
        $device_name = $_GET ['device_name'];
        $message = "A new box has been created !";
        header ( "Location: index.php?view=" . $view . "&message=" . $message . "&box=default"  . "&uuid=" . $uuid . "&device_name=" . $device_name );
    } else if ($action == "DeleteBox") {
        $box = $_GET ["box"];
        $uuid = $_GET ['uuid'];
        $device_name = $_GET ['device_name'];
        if ($box == "default") {
            $message = "Cannot delete default box !";
            header ( "Location: index.php?view=" . $view . "&message=" . $message . "&box=default"  . "&uuid=" . $uuid . "&device_name=" . $device_name );
        } else {
            $user->deleteBox ( $user_name, $box );
            $message = "The ". $box . " box is deleted !";
            header ( "Location: index.php?view=" . $view . "&message=" . $message . "&box=default" . "&uuid=" . $uuid . "&device_name=" . $device_name );
        }
    } 
    else if ($action == "MoveToBox") {
        $box = $_GET ["box"];
        $uuid = $_GET ["uuid"];
        $device_name = $_GET ["device_name"];
        $user->moveDeviceToBox ( $user_name, $uuid, $box );
        $message = "The device ". $device_name . " is moved to ". $box. " !";
        header ( "Location: index.php?view=" . $view . "&message=" . $message . "&uuid=" . $uuid . "&device_name=" . $device_name );
    } 
    else if ($action == "ChangeDeviceName") {
        $box = $_GET ["box"];
        $uuid = $_GET ["uuid"];
        $device_name = $_GET ["device_name"];
        $user->changeDeviceName ( $device_name, $uuid);
        $message = "The device is changed to ". $device_name . " !";
        header ( "Location: index.php?view=" . $view . "&message=" . $message . "&uuid=" . $uuid . "&device_name=" . $device_name );
    } 
    else if ($action == "ShareMotion") {
        $box = $_GET ["box"];
        $uuid = $_GET ["uuid"];
        $device_name = $_GET ["device_name"];
        $share_name = $_GET ["share_name"];
        if (strlen($share_name) < 2){
            $user->removeShareName ($uuid);
            $message = "Share password should be at least 3 characters, you gave ". $share_name . " !";
        }
        else {
            $user->changeShareName ( $share_name, $uuid);
            $message = "The share name is changed to ". $share_name . " !";
        }
        header ( "Location: index.php?view=" . $view . "&message=" . $message . "&uuid=" . $uuid . "&device_name=" . $device_name );
    }
    else if ($action == "ShareDevice") {
        print_r($_GET);
        $uuid = $_GET ["uuid"];
        $device_name = $_GET ["device_name"];
        
        $message = "The devices are shared with " . $user_name. " !";
        header ( "Location: index.php?view=" . $view . "&message=" . $message . "&uuid=" . $uuid . "&device_name=" . $device_name );
    } 
    else if ($action == "TagPicture") {
        $uuid = $_GET ["uuid"];
        $time = $_GET ["time"];
        $date = $_GET ["date"];
        if($_GET['submit'] == "submit") {
            $tag_name = $_GET ["tag_name"];
            $url = $_GET ["url"];
            $utils->saveTag($uuid, $tag_name, $time, $url);
            $message = "Image tagged as ". $tag_name . " !";
        }
        header ( "Location: index.php?view=" . $view . "&message=" . $message . "&uuid=" . $uuid ."&date=" . $date . '#' . str_replace ( '/', '_', $date ) );
    }
    else if ($action == "NameFace") {
        $uuid = $_GET ["uuid"];
        $alert_id = $_GET ["alert_id"];
        $user_name = $_GET ["user_name"];
        $device_name = $_GET ["device_name"];
        $face = new Face();
        if($_GET['submit'] == "submit") {
            $face_name = $_GET ["face_name"];
            $url = $_GET ["url"];
            
            //error_log("Command train =http://bingo.ibeyonde.com:5081/?cmd=face&method=train&image=".$url."&user=".$user_name."&person=".$face_name);
            $output = Utils::getSSLPage("https://bingo.ibeyonde.com:5081/?cmd=face&method=train&image=".$url."&user=".$user_name."&person=".$face_name);
            error_log("Train output=".$output);
            
            $face->saveFace($uuid, $user_name, $face_name, $alert_id, $url);
            $message = "Image tagged as ". $face_name . " !";
        }
        else if ($_GET['submit'] == "remove") {
            $ar = new AlertRaised();
            $ar->deleteAlert($alert_id);
            $message = "Face alert removed !";
        }
        header ( "Location: index.php?view=" . $view . "&message=" . $message . "&uuid=" . $uuid . "&device_name=" . $device_name);
    }
    else if ($action == "RemoveTraining") {
        $aws = new Aws();
        $aws->removeTrainingData($user_name);
        $face = new Face();
        $face->deleteTrainedFaces($user_name);
        $message = "Removed all training data for $user_name !";
        header ( "Location: index.php?view=" . $view . "&message=" . $message );
    }
    else if ($action == "AlertConfig") {
        //error_log("AlertConfig".print_r($_GET, true));
        $uuid = $_GET ["uuid"];
        $device_name = $_GET ["device_name"];
        $timezone = $_GET ["timezone"];
        $ac = new AlertConfig();
        $ac->parseAndUpdate($uuid, $user_email, $_GET);
        header ( "Location: index.php?view=" . $view . "&message=" . $message . "&uuid=" . $uuid . "&device_name=" . $device_name  . "&timezone=" . $timezone. "&tab=". $tab);
    }
    else {
        $message = "Unauthorized Command !";
        header ( "Location: index.php?view=" . $view . "&message=" . $message );
    }
}


?>
