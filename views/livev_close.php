<?php
    define ( '__ROOT__', dirname ( dirname ( __FILE__ )));
    require_once(__ROOT__.'/config/config.php');
    require_once(__ROOT__.'/classes/DeviceContext.php');
    require_once(__ROOT__.'/classes/Mp4.php');
    
    $uuid=$_GET['uuid'];
    $stream_id=$_GET['sid'];
    Mp4::releaseOnership($uuid, $stream_id);
?>