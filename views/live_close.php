<?php

    header('Access-Control-Allow-Origin: *');

    define ( '__ROOT__', dirname ( dirname ( __FILE__ )));
    require_once(__ROOT__.'/config/config.php');
    require_once(__ROOT__.'/classes/DeviceContext.php');
    require_once(__ROOT__.'/classes/Mjpeg.php');
    
    
    $uuid=$_GET['uuid'];
    $stream_id=$_GET['sid'];
    
    $mjpeg = new Mjpeg();
    $mjpeg->releaseOnership($uuid, $stream_id);
?>