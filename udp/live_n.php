<?php
    define ( '__ROOT__', dirname ( dirname ( __FILE__ )));
    require_once(__ROOT__.'/classes/NetUtils.php');
    require_once(__ROOT__.'/classes/Utils.php');
    require_once(__ROOT__.'/classes/Quality.php');
    require_once(__ROOT__.'/classes/Mjpeg.php');
    require_once(__ROOT__.'/classes/FIFO.php');
    
    $token = $_GET['tk'];
    $user_name = $_GET['user_name'];
    $uuid=$_GET['uuid'];
    $port=$_GET['port'];
    $stream_id=$_GET['sid'];
    $timezone=$_GET['timezone'];
    
    error_log($stream_id."-----Starting Live -----".$uuid);
    set_time_limit(300);
    ignore_user_abort(false);
    ob_get_clean();
    
    $mjpeg = new Mjpeg();
    $mjpeg->init($uuid);
    
    $utils = new Utils();
    $check_token = $utils->checkToken($token, $uuid);
    if ($check_token != 0){
        $mjpeg->display(Mjpeg::D_UNAUTHORIZED);
        die;
    }
    
    $check_uuid = $utils->validateDevice($user_name, $uuid);
    if ($check_uuid != $uuid){
        $mjpeg->display(Mjpeg::D_UNAUTHORIZED);
        die;
    }
    
    $fileResp = array("R".Quality::A, "R".Quality::B, "R".Quality::C, "R".Quality::D, "R".Quality::E, "R".Quality::F, "R".Quality::G);
    if (isset($_GET['quality'])){
        $quality=$_GET['quality'];
    }
    else {
        $quality = Quality::E;
    }
    error_log("live_n : Username=".$user_name." uuid=".$uuid);
    $bytes=0;
    $last_ts = 0;
    $peeraddr = null;
    $peerport = null;
    $toggle = true;
    $error =  0;
    
    register_shutdown_function(
            function ($mjpeg, $stream_id, $uuid){
                if (isset($netutils)){
                    $netutils->close();
                    unset($netutils);
                }
                //$utils = new Utils();
                error_log($stream_id." Completed XXXXXXXXXXXXXXXXXXXXXX".$uuid);
                $mjpeg->releaseOnership($uuid, $stream_id);
                //$utils->publishNetwork($uuid, date('Y/m/d H:i:s e', Utils::datetimeNow($timezone)), $bytes);
            },
            $mjpeg, $stream_id, $uuid
    );
    
    
    error_log("live_n : quality = $quality");
    $fifo = new FIFO(10);
    for( $i = 0; $i<3000; $i++ ){
        if(connection_status() != CONNECTION_NORMAL){
            error_log("live_n : Connection broken XXXXXXXXXXXX ". $uuid);
            break;
        }
        if ($mjpeg->establishOwnership($uuid, $stream_id)){
            try {
                if ($toggle){
                    $netutils = new NetUtils($user_name, $uuid, $port);
                    if (!isset($_GET['reload'])){
                        $netutils->register();
                        $td = $netutils->getTimeDeltaForLastPing();
                        if ($td > 500 ){
                            error_log("LIVE N Rcvd D_NO_SIGNAL");
                            $mjpeg->display(Mjpeg::D_NO_SIGNAL);
                            sleep(2);
                            $mjpeg->display(Mjpeg::D_NO_SIGNAL);
                            sleep(2);
                            break;
                        }
                    }
                    else {
                        $netutils->getSocket();
                    }
                    $toggle = false;
                }
                $netutils->initiate($quality);
                list($cmd, $data) = $netutils->recvCommandBroker();
                if (in_array($cmd, $fileResp)){
                    list($peeraddr, $peerport) = $netutils->bytes2address($data);
                    //error_log("LIVE:uuid =" .$uuid." peeraddr=".$peeraddr." and peerport=".$peerport);
                }
                list($cmd, $data) = $netutils->recvCommandPeer($peeraddr, $peerport);
                if ($cmd == 'SIZE'){
                    $dt = explode(".", $data);
                    if (strcmp($dt[0], $uuid) !== 0) {
                        error_log("LIVE Fatal the uuids do not match imguuid=".$dt[0]." device uuid=".$uuid);
                        sleep(2);
                        $netutils = new NetUtils($user_name, $uuid, $port);
                        $netutils->register();
                        $netutils->sendActionBroker ($uuid, 'Reset', '' );
                        $mjpeg->display(Mjpeg::D_ERROR);
                        $mjpeg->display(Mjpeg::D_ERROR);
                        break;
                    }
                    $img = $netutils->recvAllPeer(intval($dt[1]), $peeraddr, $peerport);
                    $imgsize = Mjpeg::displayImage($img);
                    //error_log("MJPEG: Rcving SIZE ".intval($dt[1]). " for ".$uuid);
                    $bytes=$bytes+$imgsize;
                    $mjpeg->updateImage($uuid, $img, $timezone);
                    $quality = Quality::getQuality($fifo, $quality);
                    //error_log("Quality is ". $quality);
                    $fifo->queue(1);
                    $error=0;
                    flush();
                }
                else if ($cmd == 'NOTONLINE' || $cmd == 'UNSUPPORTED'){
                    error_log("LIVE N Rcvd NOTONTLINE");
                    $mjpeg->display(Mjpeg::D_NO_SIGNAL);
                    $mjpeg->display(Mjpeg::D_NO_SIGNAL);
                    break;
                }
            } catch (Exception $e){
                $fifo->queue(0);
                if ($error > 10){
                    error_log("LIVE Exception ".$uuid.", stream id ".$stream_id);
                    $mjpeg->display(Mjpeg::D_ERROR);
                    $mjpeg->display(Mjpeg::D_ERROR);
                   break; 
                }
                else{
                    sleep(++$error);
                }
                unset($netutils);
                $netutils = new NetUtils($user_name, $uuid, $port);
                $netutils->getSocket();
                $netutils->initiate($quality);
            }
        }
        else { // someone else owns this
            list($img, $new_ts) = $mjpeg->getNext($uuid, $last_ts);
            //error_log($uuid." lt=".$last_ts."---live n Ts = " .$new_ts);
            if ($img == -1){
                $error++;
                if ($error > 10){
                    $error = 0;
                    $mjpeg->forceOwnership($uuid, $stream_id);
                }
                $last_ts = $new_ts;
                sleep(1);
            }
            else {
                $imgsize = $mjpeg->displayImage($img);
                $bytes=$bytes+$imgsize;
            }
        }
    }
    
   
?>