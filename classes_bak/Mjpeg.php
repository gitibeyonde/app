<?php 

require_once(__ROOT__.'/classes/NetUtils.php');
require_once(__ROOT__.'/classes/DeviceContext.php');
require_once(__ROOT__.'/classes/Aws.php');


class Mjpeg {
    const ROOT = "/srv/www/udp1.ibeyonde.com/public_html/live_cache/" ;
    const D_ERROR = "/img/error.jpg";
    const D_NO_SIGNAL = "/img/no_signal.jpg";
    const D_UNAUTHORIZED = "/img/unauthorized.jpg";
    const D_LOADING = "/img/loading.jpg";
    
    public $aws;
    
    public function __construct()
    {
        $this->aws = new Aws();
    }
    
    public function init($uuid){
        header($_SERVER["SERVER_PROTOCOL"]." 200 OK");
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Cache-Control: no-store");
        header('Content-Type: multipart/x-mixed-replace; boundary=--jpgboundary');
        print("--jpgboundary\n");
        if (!isset($_GET['reload'])){
            Mjpeg::display(Mjpeg::D_LOADING);
        }
        if (!file_exists(Mjpeg::ROOT.$uuid)) {
            mkdir(Mjpeg::ROOT.$uuid, 0777, true);
        }
    }
    
    public function display($type){
        print('Content-Type: image/jpeg'."\n");
        print("Content-Length: ". filesize(__ROOT__.$type) . "\n\n" );
        readfile(__ROOT__.$type);
        print("--jpgboundary\n");
        flush();
        print('Content-Type: image/jpeg'."\n");
        print("Content-Length: ". filesize(__ROOT__.$type) . "\n\n" );
        readfile(__ROOT__.$type);
        print("--jpgboundary\n");
        flush();
    }
    
    public function displayImage($img){
        print("Content-Type: image/jpeg\n");
        $imgsize = strlen($img);
        //error_log("---live n got image size = ".$imgsize);
        print("Content-length: ". $imgsize . "\n\n" );
        print $img;
        print("--jpgboundary\n");
        flush();
        return $imgsize;
    }
     
    public function getNext($uuid, $last_ts){
        clearstatcache();
        $mod_time = filemtime(Mjpeg::ROOT.$uuid."/cur.jpg");
        if ($last_ts < $mod_time) {
            //error_log("Last time = ".$last_ts . " Mod ts=" . $mod_time);
            return array(file_get_contents(Mjpeg::ROOT.$uuid."/cur.jpg"), $mod_time);
        }
        else {
            return array(-1, $mod_time);
        }
    }
    
    public function updateImage($uuid, $img, $timezone){
        //error_log("Copying img " . $uuid);
        file_put_contents(Mjpeg::ROOT.$uuid."/cur.jpg", $img);
        $rec_start = $this->checkRecording($uuid, $timezone);
        if ($rec_start != false){
            $time_now = new DateTime("now", new DateTimeZone($timezone));
            $time_now_str = $time_now->format('Y/m/d/H_i_s'); 
            $this->aws->uploadFile($uuid, 'record/'.$rec_start."/".$time_now_str."/".Utils::randomString(6).".jpg", 
                    Mjpeg::ROOT.$uuid."/cur.jpg", 'image/jpeg');
        }
    }
    
    public static function startRecording($uuid, $timezone){
        $time_now = new DateTime("now", new DateTimeZone($timezone));
        $rec_start = $time_now->format(DateTime::ATOM);
        file_put_contents(Mjpeg::ROOT.$uuid."/.record", $rec_start);
        return $rec_start;
    }
    
    public static function stopRecording($uuid, $timezone){
        file_put_contents(Mjpeg::ROOT.$uuid."/.record", "");
    }
    
    public function checkRecording($uuid, $timezone){
        if (!file_exists(Mjpeg::ROOT.$uuid."/.record")) {
            return false;
        }
        $rec_start = file_get_contents(Mjpeg::ROOT.$uuid."/.record");
        if ($rec_start == "") {
            return false;
        }
        $dtz = new DateTimeZone($timezone);
        $rec_start_time= DateTime::createFromFormat(DateTime::ATOM, $rec_start, $dtz);
        $time_now = new DateTime("now", new DateTimeZone($timezone));
        //error_log("Start time = " . $rec_start . "  Current time = " . $time_now->format(DateTime::ATOM) . " timezone =". $timezone);
        if (($time_now->getTimestamp() - $rec_start_time->getTimestamp()) < 300 ){
            return $rec_start;
        }
        else {
            $this->stopRecording($uuid, $timezone);
            return false;
        }
    }
    
    public static function isRecording($uuid){
        if (!file_exists(Mjpeg::ROOT.$uuid."/.record")) {
            return false;
        }
        $rec_start = file_get_contents(Mjpeg::ROOT.$uuid."/.record");
        if ($rec_start == "") {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function establishOwnership($uuid, $stream_id){
        $context = new DeviceContext();
        $cid = $context->getDeviceContext($uuid, 'live');
        if ($cid == 'closed' || $cid == null) {
            $context->updateDeviceContext($uuid, "live", $stream_id);
            //error_log(">>>>>>>>>>>>>>>>>>>Mjpeg: establising ownership " .$stream_id);
            return true;
        }
        else if ($cid == $stream_id){
            //error_log(">>>>>>>>>>>>>>>>>>>Mjpeg: ownership " .$stream_id);
            return true;
        }
        else {
            //error_log(">>>>>>>>>>>>>>>>>>>Mjpeg: ownership with some other thread " .$cid);
            return false;
        }
    }
    
    public function forceOwnership($uuid, $stream_id){
        //error_log(">>>>>>>>>>>>>>>>>>>Mjpeg: ownership forced");
        $context = new DeviceContext();
        $context->updateDeviceContext($uuid, "live", $stream_id);
    }
    
    public function releaseOnership($uuid, $stream_id){
        $context = new DeviceContext();
        $cid = $context->getDeviceContext($uuid, 'live');
        if ($cid == $stream_id){
            $context->updateDeviceContext($uuid, "live", 'closed');
            //error_log("<<<<<<<<<<<<<<<<Mjpeg: releasing ownership " .$stream_id);
        }
    }
}

?>
