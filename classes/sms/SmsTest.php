<?php
define ( '__ROOT__',  dirname(dirname(dirname ( __FILE__ ))));
error_log("Root=".__ROOT__);
require_once (__ROOT__ . '/classes/sms/SmsImages.php');
require_once (__ROOT__ . '/classes/sms/AwsStore.php');
require_once (__ROOT__ . '/classes/sms/SmsMinify.php');
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/libraries/aws.phar');
require_once (__ROOT__ . '/classes/sms/AwsSnsSms.php');
require_once(__ROOT__.'/config/config.php');


$log = $GLOBALS['log'] = new Log("info");
$sns = new AwsSnsSms();

echo $sns->sendSms("+919701199011", "File manager");
?>