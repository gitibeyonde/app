
<?php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
include_once(__ROOT__ .'/classes/core/Log.php');
include_once (__ROOT__.'/classes/wf/SmsWfProcessor.php');

$_SESSION['log'] = new Log("info");

if (isset($_GET['bot_id'])){
    $bot_id = $_GET['bot_id'];
    $user_id = $_GET['user_id'];
    $mobile = $_GET['random_mobile'];
    $sms = $_GET['sms'];
}
else {
    $bot_id = $_POST['bot_id'];
    $user_id = $_POST['user_id'];
    $mobile = $_POST['random_mobile'];
    $sms = $_POST['sms'];
}

if ($bot_id==null)die;

echo SmsWfProcessor::processChat($user_id, $bot_id, $mobile, $sms);
?>
