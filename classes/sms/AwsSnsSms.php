<?php

require_once (__ROOT__ . '/libraries/aws.phar');
require_once(__ROOT__.'/classes/Motion.php');
require_once(__ROOT__.'/classes/DeviceToken.php');
require_once(__ROOT__.'/classes/AlertRaised.php');
require_once(__ROOT__.'/classes/Device.php');
require_once(__ROOT__.'/config/config.php');

class AwsSnsSms {
    private static $sns = null;
    private static $credentials = array (
            'version' => S3_VERSION,
            'region' => "ap-south-1",
            'credentials' => [
                    'key'    => S3_KEY,
                    'secret' => S3_SECRET,
            ],
    );
    
    
    private static $app_arn = "arn:aws:sns:us-west-2:574451441288:app/GCM/ibeyonde";
    
    public function __construct() {
        if (self::$sns == null) {
            self::$sns = Aws\Sns\SnsClient::factory ( self::$credentials  );
        }
    }
    
    public function sendSms($number, $sms){
        error_log("Sending OTP to ".$number." for ".$sms);
        try {
            $result = AwsSnsSms::$sns->SetSMSAttributes([
                    'attributes' => [
                            'DefaultSMSType' => 'Transactional',
                    ],
            ]);
            $result = AwsSnsSms::$sns->publish([
                    'Message' => $sms,
                    'PhoneNumber' => $number,
                    'MessageAttributes' => ['AWS.SNS.SMS.SenderID' => [
                            'DataType' => 'String',
                            'StringValue' => 'Ibeyonde'
                    ]
                    ]]);
            error_log("Result ".print_r($result, true));
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }   
}
?>