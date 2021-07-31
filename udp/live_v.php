<?php
define ( '__ROOT__', dirname ( dirname ( __FILE__ ) ) );
require_once (__ROOT__ . '/classes/NetUtils.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/VQuality.php');
require_once (__ROOT__ . '/classes/Mp4.php');

header ( 'Content-type: application/txt' );
header ( 'Access-Control-Allow-Origin: *' );

set_time_limit ( 300 );
ignore_user_abort ( false );
ob_get_clean ();

$token = $_GET ['tk'];
$user_name = $_GET ['user_name'];
$uuid = $_GET ['uuid'];
$port = $_GET ['port'];
$stream_id = $_GET ['sid'];
// $stream_id = mt_rand();
$timezone = $_GET ['timezone'];

$mp4 = new Mp4 ( $uuid );
$utils = new Utils ();
$check_token = $utils->checkToken ( $token, $uuid );
if ($check_token != 0) {
    error_log ( $uuid . " Unauthorized Token " . $token . " response " . $check_token );
    $mp4->display ( $uuid, Mp4::D_UNAUTHORIZED );
    die ();
}

$check_uuid = $utils->validateDevice ( $user_name, $uuid );
if ($check_uuid != $uuid) {
    error_log ( $uuid . " Unauthorized Device " . $user_name );
    $mp4->display ( $uuid, Mp4::D_UNAUTHORIZED );
    die ();
}

$fileResp = array ("R" . VQuality::A,"R" . VQuality::B,"R" . VQuality::C,"R" . VQuality::D,"R" . VQuality::E,"R" . VQuality::F,"R" . VQuality::G,"R" . VQuality::H,"R" . VQuality::I,"R" . VQuality::J,
        "R" . VQuality::K,"R" . VQuality::L,"R" . VQuality::M,"R" . VQuality::N,"R" . VQuality::O,"R" . VQuality::P 
);
if (isset ( $_GET ['quality'] )) {
    $topQ = $_GET ['quality'];
} else {
    $topQ = VQuality::G;
}
error_log ( "liveV.php: Username=" . $user_name . " uuid=" . $uuid . " top Q " . $topQ );

$peeraddr = '';
$peerport = '';
$loop = True;
$bytes = 0;
$toggle = true;
$error = 0;

register_shutdown_function ( function ($mp4, $stream_id, $uuid) {
    if (isset ( $netutils )) {
        $netutils->close ();
        unset ( $netutils );
    }
    
    $mp4->releaseOnership ( $uuid, $stream_id );
    // $utils->publishNetwork ( $uuid, date ( 'Y/m/d H:i:s e', Utils::datetimeNow ( $timezone ) ), $bytes );
    error_log ( $stream_id . " CLosing XXXXXXXXXXXXXXXXXXXXXX" . $uuid );
}, $mp4, $stream_id, $uuid );

$qv = new VQuality ();
$quality = $mp4->getQuality ( $uuid, VQuality::N );

// error_log("live_n : quality = $quality");
for($i = 0; $i < 1000; $i ++) {
    if (connection_status () != CONNECTION_NORMAL) {
        error_log ( "live_v : Connection broken XXXXXXXXXXXX " . $uuid );
        break;
    }
    if ($mp4->establishOwnership ( $uuid, $stream_id )) {
        try {
            if ($toggle) {
                error_log ( $stream_id . "-----Starting Live Video-----" . $uuid );
                $netutils = new NetUtils ( $user_name, $uuid, $port );
                $netutils->register ();
                $td = $netutils->getTimeDeltaForLastPing ();
                if ($td > 500) {
                    error_log ( $stream_id . "-----Stopped Live Video as device unresponsive-----" . $uuid );
                    die ();
                }
                $toggle = false;
            }
            $netutils->initiateV ( $quality );
            // usleep(400);
            list ( $cmd, $data ) = $netutils->recvCommandBroker ();
            // error_log ( "LIVE: recvCommandBroker cmd =" . $cmd . " data=" . $data );
            
            if (in_array ( $cmd, $fileResp )) {
                list ( $peeraddr, $peerport ) = $netutils->bytes2address ( $data );
                // error_log ( "LIVE: uuid =" . $uuid . " peeraddr=" . $peeraddr . " and peerport=" . $peerport );
            }
            list ( $cmd, $data ) = $netutils->recvCommandPeer ( $peeraddr, $peerport );
            // error_log ( "LIVE: recvCommandPeer cmd =" . $cmd . " data=" . $data );
            
            if ($cmd == 'SIZE') {
                $dt = explode ( ".", $data ); // uuid.imglen.index-timestamp-cache_size
                if (strcmp ( $dt [0], $uuid ) !== 0) {
                    error_log ( "LIVE VIDEO Fatal the uuids do not match imguuid=" . $dt [0] . " device uuid=" . $uuid );
                    $mp4->display ( $uuid, Mp4::D_ERROR );
                    sleep ( 1 );
                    $netutils = new NetUtils ( $user_name, $uuid, $port );
                    $netutils->register ();
                    $netutils->sendActionBroker ( $uuid, 'Reset', '' );
                    die ();
                }
                if (intval ( $dt [1] ) <= 0) {
                    // error_log ( "LIVE MP4: Rcving size 0 ");
                    sleep ( 1 );
                    continue;
                } else {
                    error_log ( "LIVE MP4:size=" . intval ( $dt [1] ) . " index=" . $dt [2] . " dev=" . $uuid . "  q=" . $quality );
                    $vid = $netutils->recvAllPeer ( intval ( $dt [1] ), $peeraddr, $peerport );
                    if (intval ( $dt [1] ) == 0)
                        continue;
                    $r1 = $mp4->updateVideo ( $uuid, $vid, $dt [2], $timezone );
                    // error_log("Quality= ".$quality.", r1=".$r1);
                    $quality = $qv->getQuality ( ($r1 == 1), $topQ, $quality );
                    $bytes = $bytes + strlen ( $vid );
                    $mp4->setQuality ( $uuid, $quality );
                }
                flush ();
            } else if ($cmd == 'NOTONLINE' || $cmd == 'UNSUPPORTED') {
                error_log ( "LIVE Rcvd NOTONTLINE" );
                $mp4->display ( $uuid, Mp4::D_NO_SIGNAL );
                sleep ( 1 );
                continue;
            }
            usleep ( 500 );
        } catch ( Exception $e ) {
            // error_log ( "LIVE Exception " . $uuid . ", " . $e->getTraceAsString () . ", error " . $error);
            if ($error > 5) {
                $mp4->display ( $uuid, Mp4::D_ERROR );
                break;
            } else {
                sleep ( ++ $error );
            }
            // unset ( $netutils );
            // $netutils = new NetUtils ( $user_name, $uuid, $port );
            // $netutils->getSocket ();
            $netutils->initiateV ( $quality );
        }
    } else {
        break;
    }
}

?>
