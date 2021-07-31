<?php

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/sms/SmsSend.php');

if (isset($_GET['phone']) && isset($_GET['template'])){
    $map=array();
    foreach (array_keys($_GET) as $gk){
        if ($gk == "template") {
            $template = $_GET[$gk];
        }
        else if ($gk == 'phone'){
            $phone = $_GET[$gk];
        }
        else{
            $map[$gk] = $_GET[$gk];
        }
    }
    
    foreach (array_keys($_POST) as $gk){
        if ($gk == "template") {
            $template = $_POST[$gk];
        }
        else if ($gk == 'phone'){
            $phone = $_POST[$gk];
        }
        else{
            $map[$gk] = $_POST[$gk];
        }
    }
    
    $host_key = $map['host_key'];
    
    $SU = new SmsUtils();
    list($uid, $exp) = $SU->checkHostKey($host_key);
    if ($exp < date(DateTime::ATOM)){
        echo "ERROR: your host key has expired, generate a new one\n";
    }
    
    $Lperson=array();
    $Lperson['number']=$phone;
    $fields = $SU->templateInputFields($template);
    foreach ($fields as $field){
        error_log($field."=".$map[$field]);
        if ($field != "otp" && $field != "4otp" && $field != "6otp"){
            $Lperson[$field]=$map[$field];
        }
    }
    $otp= rand(100000, 999999);
    $Lperson["otp"]=$otp;
    SmsSend::sendTemplateToPerson($uid, "trigger", $template, $Lperson);
    return $otp;
}
else {
    return "-1";
}

?>
