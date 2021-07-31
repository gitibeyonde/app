<?php
define ( '__ROOT__', dirname ( dirname ( __FILE__ )));
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/NetUtils.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/Quality.php');


header ( $_SERVER ["SERVER_PROTOCOL"] . " 200 OK" );
header ( "Pragma: no-cache" );
header ( "Cache-Control: no-cache" );
header ( "Cache-Control: private" );
header ( "Content-type: image/jpeg" );

$uuid = $_GET ['uuid'];
$port=$_GET['port'];
$user_name = $_SESSION ['user_name'];

$quality = Quality::C;
if (isset ( $_GET ['quality'] )) {
    $quality = $_GET ['quality'];
}
$utils = new Utils();
$check_uuid = $utils->validateDevice($user_name, $uuid);
if ($check_uuid != $uuid){
    readfile(__ROOT__.'/img/unauthorized.jpg');
    //error_log("Invalid");
    flush();
    exit;
}
$netutils = new NetUtils($user_name, $uuid, $port);
$netutils->register();
$td = $netutils->getTimeDeltaForLastPing();
if ($td > 500 ){
    header('Content-Type: image/jpeg');
    readfile(__ROOT__.'/img/no_signal.png');
    //error_log("No Signal");
    flush();
    exit;
}


$fileCmds =  array("R".Quality::A, "R".Quality::B, "R".Quality::C, "R".Quality::D, "R".Quality::E);

error_log("START---------------------------------------");
ob_end_flush();
try {
    $netutils->initiate ( $quality );
    list ( $cmd, $data ) = $netutils->recvCommandBroker ();
    error_log("SNAP Command = $cmd, Data = $data");
    
    if (in_array ( $cmd, $fileCmds )) {
        list ( $peeraddr, $peerport ) = $netutils->bytes2address ( $data );
        error_log("SNAP live: peeraddr=".$peeraddr." and peerport=".$peerport);
    } else {
        die;
    }

    list ( $cmd, $data ) = $netutils->recvCommandPeer ( $peeraddr );
    error_log("SNAP Command = $cmd, Data = $data");
    
    if ($cmd == 'SIZE') {
        // error_log("SNAP Received SIZE =" .intval($data));
        $dt = explode ( ".", $data );
        if (strcmp ( $dt [0], $uuid ) !== 0) {
            error_log ( "Fatal the uuids do not match imguuid=" . $dt [0] . " device uuid=" . $uuid );
            exit;
        }
        error_log("SNAP: Rcving SIZE ".intval($dt[1]). " for ".$uuid);    
        $img = $netutils->recvAllPeer ( intval ( $dt [1] ), $peeraddr );
        $imgsize = strlen($img);
        print $img;
    } else {
        readfile(__ROOT__.'/img/error.jpg');
    }
} catch ( Exception $e ) {
    error_log ( "SNAP " . $e->getTraceAsString () );
    readfile(__ROOT__.'/img/error.jpg');
}
// $netutils->close();
error_log("SNAP------------------------------------------\n");
$netutils->close();
flush();
exit;
?>