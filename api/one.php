<?php

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/classes/sms/SmsMinify.php');

if (isset($_GET['id'])){
    $id=$_GET['id'];
    $p=null;
    if (strpos($id, ",") !== false) {
        $idv = explode(",", $id, 2);
        $id = $idv[0];
        $p = $idv[1];
    }
    //error_log("Id id=".$id." p=".$p);
    $od = new SmsMinify();
    $map =  $od->getUrl($id);
    $ip = getenv('HTTP_CLIENT_IP')?:
    getenv('HTTP_X_FORWARDED_FOR')?:
    getenv('HTTP_X_FORWARDED')?:
    getenv('HTTP_FORWARDED_FOR')?:
    getenv('HTTP_FORWARDED')?:
    getenv('REMOTE_ADDR');
    if (!isset($_SERVER['HTTP_USER_AGENT'])){
        header("Location:  /index.php", true, 307); 
        die;
    }
    $ag = $_SERVER['HTTP_USER_AGENT'];
    if ($map==null){
        header("Location: /api/removed.html", true, 301);
    }
    else {
        if ($p!=null){
            $od->logAccess($id, $ip, $ag);
            header("Location: ". $map."?p=".$p."&i=".$id, true, 307);
        }
        else{
            $od->logAccess($id, $ip, $ag);
            header("Location: ". $map, true, 308); 
        }
    }
}
else {
    header("Location:  /index.php", true, 301); 
}
?>

