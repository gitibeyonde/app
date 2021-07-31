<?php 
require_once(__ROOT__.'/config/config.php');

require_once(__ROOT__.'/classes/utils/Mobile_detect.php');
$Md = new Mobile_Detect();
if ($Md->isMobile()){
    $_SESSION["mobile"] = true;
}
else {
    $_SESSION["mobile"] = false;
}

$_SERVER['SERVER_NAME'];
if ($_SERVER['SERVER_NAME']=="app.ibeyonde.com"){
    include (__ROOT__ . '/views/app/app_header.php'); 
} 
else {
    include (__ROOT__ . '/views/sms/sms_header.php'); 
} 
?>
</head>


