<?php

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/classes/AwsSqs.php');
require_once(__ROOT__.'/classes/sms/SmsUtils.php');

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
    
    $SU = new SmsUtils();
    $ts = $SU->getSmsTemplates($_SESSION['user_id']);
    
    $nts = $SU->templateReplace($ts,$map);
    error_log("Sending Sms ".$nts);
    
    $phone=$_GET['phone'];
    $template=$_GET['template'];
    //^(?:(?:\+|0{0,2})91(\s*[\-]\s*)?|[0]?)?[789]\d{9}$
    if(preg_match("/^(?:(?:\+|0{0,2})91(\s*[\-]\s*)?|[0]?)?[789]\d{9}$/", $phone)) {
        $otp = mt_rand(100000, 999999);
        $aws = new AwsSqs();
        echo $nts;
    }
    else {
        echo "ERROR: Bad number".$phone;
    }}
    ?>

