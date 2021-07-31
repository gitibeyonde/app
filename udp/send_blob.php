<?php
define ( '__ROOT__', dirname ( dirname ( __FILE__ ) ) );
require_once (__ROOT__ . '/classes/NetUtils.php');
require_once (__ROOT__ . '/classes/Utils.php');

header('Content-type: application/txt');
header('Access-Control-Allow-Origin: *');

$token = $_POST['tk'];
$user_name = $_POST['user_name'];
$user_id = $_POST['user_id'];
$uuid=$_POST['uuid'];
$port=$_POST['port'];
$timezone=$_POST['timezone'];
$size=$_POST['size'];


$utils = new Utils ();
$check_token = $utils->checkToken($token, $uuid);
if ($check_token != 0){
    error_log("FATAL:".$uuid." Unauthorized Token " . $token . " response ".$check_token);
    die;
}

$check_uuid = $utils->validateDevice($user_name, $uuid);
if ($check_uuid != $uuid){
    error_log("FATAL:".$uuid." Unauthorized Device ".$user_name);
    die;
}

$netutils = new NetUtils ( $user_name, $uuid, $port );
$netutils->register ();
$td = $netutils->getTimeDeltaForLastPing ();
if ($td > 500) {
    die ();
}

$netutils->sendCommandBroker("PADDR:".$uuid.":");;

list ( $cmd, $data ) = $netutils->recvCommandBroker ();

list ( $peeraddr, $peerport ) = $netutils->bytes2address ( $data );

error_log ( "Broker-----------------".$cmd.": uuid =" . $uuid . " peeraddr=" . $peeraddr . " and peerport=" . $peerport );
//[Mon Aug 13 10:20:26.053046 2018] [:error] [pid 22170] [client 1.186.105.243:65200] -----------------PADDR: uuid =b6a59f74 peeraddr=1.186.105.243 and peerport=14569

list ( $cmd, $data ) = $netutils->recvCommandPeer ($peeraddr);

error_log ( "Peer-----------------".$cmd.": data=" . $data );
//-----------------PADDR: datab6a59f74

$netutils->sendCommandPeer($peeraddr, $peerport, 'SBLB', $size);

$netutils->sendAllPeer($peeraddr, $peerport, file_get_contents( $_FILES['data']['tmp_name'] ), $size);

error_log ("Complete sent ------");

//error_log("File content = ". file_get_contents( 'php://input' ));
//error_log(print_r(apache_request_headers(), true));
//error_log(print_r($_SERVER, true));
//error_log("POST DATA".print_r($_POST, true));
//error_log("GET DATA".print_r($_GET, true));
//error_log("FILES".print_r($_FILES, true));
//error_log("File content = ".  $_FILES['data']['tmp_name'] );
//error_log("File content = ". base64_encode( file_get_contents( $_FILES['data']['tmp_name'] )));
//error_log("-----------------------------------------------------------------------------------------------------------------------");




?>
