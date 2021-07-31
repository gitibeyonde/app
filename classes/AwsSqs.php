<?php
require_once (__ROOT__ . '/libraries/aws.phar');
require_once(__ROOT__.'/config/config.php');

class AwsSqs {
    private static $sqs = null;
    private static $otpQueue = "https://sqs.us-west-2.amazonaws.com/574451441288/otp";
    private static $credentials =  array (
            'version' => S3_VERSION,
            'region' => S3_REGION,
            'credentials' => [
                    'key'    => S3_KEY,
                    'secret' => S3_SECRET,
            ],
    );
    
    private static $app_arn = "arn:aws:sqs:us-west-2:574451441288:otp";
    public function __construct() {
        if (self::$sqs == null) {
            self::$sqs = Aws\Sqs\SqsClient::factory ( self::$credentials  );
        }
    }
    
    public function sendSms($user_id, $bot_id, $type, $tid, $text, $phone) { //sendSms($nts, $phone, $preferred, $avoid);
        $attributes = array (
            'cmd' => array(
                'DataType' => 'String',
                'StringValue' => $type
            ),
            'phone' => array(
                    'DataType' => 'String',
                    'StringValue' => $phone
            ),
            'uid' => array(
                    'DataType' => 'String',
                    'StringValue' => $user_id
            ),
            'tid' => array(
                    'DataType' => 'Number',
                    'StringValue' => $tid
            ),
            'bid' => array(
                    'DataType' => 'String',
                    'StringValue' => $bot_id
            )
        );
        $result = self::$sqs->sendMessage ( array (
                'QueueUrl' => self::$otpQueue,
            'MessageBody' => $text,
            'MessageAttributes' => $attributes
        ) );
        error_log("SQS sendSms Result=".print_r($result, true));
    }
    
    public function sendSmsToDevice($device_id, $user_id, $type, $tid, $text, $phone) { //sendSms($nts, $phone, $preferred, $avoid);
        $attributes = array (
                'cmd' => array(
                        'DataType' => 'String',
                        'StringValue' => $type
                ),
                'phone' => array(
                        'DataType' => 'String',
                        'StringValue' => $phone
                ),
                'uid' => array(
                        'DataType' => 'String',
                        'StringValue' => $user_id
                ),
                'tid' => array(
                        'DataType' => 'Number',
                        'StringValue' => $tid
                ),
                'uuid' => array(
                        'DataType' => 'Number',
                        'StringValue' => $device_id
                )
        );
        $result = self::$sqs->sendMessage ( array (
                'QueueUrl' => self::$otpQueue,
                'MessageBody' => $text,
                'MessageAttributes' => $attributes
        ) );
        error_log("SQS sendSms Result=".print_r($result, true));
    }
    public function sendPing($user_id, $device_uuid, $master_phone, $message) { //sendSms($nts, $phone, $preferred, $avoid);
        $queueUrl = self::$baseUrl."register";
        $attributes = array (
                'cmd' => array(
                        'DataType' => 'String',
                        'StringValue' => 'ping'
                ),
                'uuid' => array(
                        'DataType' => 'String',
                        'StringValue' => $device_uuid
                ),
                'phone' => array(
                        'DataType' => 'String',
                        'StringValue' => $master_phone
                )
        );
        $result = self::$sqs->sendMessage ( array (
                'QueueUrl' => $queueUrl,
                'MessageBody' => $message,
                'MessageAttributes' => $attributes
        ) );
        error_log("SQS sendPing Result=".print_r($result, true));
    }
    
    public function recvMessageFromQueue($queue_name){
        $queueUrl = self::$baseUrl.$queue_name;
        $result = self::$sqs->receiveMessage(array(
                'AttributeNames' => ['SentTimestamp'],
                'MaxNumberOfMessages' => 1,
                'MessageAttributeNames' => ['All'],
                'QueueUrl' => $queueUrl, // REQUIRED
                'WaitTimeSeconds' => 20,
        ));
        error_log("SQS recvMessageFromQueue=".$queue_name."==".print_r($result, true));
    }
}
?>