<?php
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/sms/SmsMessage.php');
require_once (__ROOT__ . '/classes/sms/EmailLog.php');
require_once(__ROOT__.'/libraries/aws.phar');
use Aws\Common\Enum\Region;
use Aws\Ses\SesClient;
require_once(__ROOT__.'/config/config.php');


class EmailUtils
{
    private $db_connection            = null;
    public  $errors                   = array();
    public  $messages                 = array();
    private $client                   = null;

    public function __construct()
    { 
        $this->client = SesClient::factory(array(
                'version' => SES_VERSION,
                'region' => SES_REGION,
                'credentials' => [
                        'key'    => SES_KEY,
                        'secret' => SES_SECRET
                ]
        ));
    }
    
    private function databaseConnection()
    {
        // connection already opened
        if ($this->db_connection != null) {
            return true;
        } else {
            // create a database connection, using the constants from config/config.php
            try {
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR;
                return false;
            }
        }
    }

    public function sendEmailAlert($id, $there_email, $subject, $body)
    {
        error_log("Email ".$there_email.",".$subject. " Dest=". $body);
         $msg_id=0;
         $msg = array();
         $msg['Source'] = "app@ibeyonde.com";
         $msg['Destination']['ToAddresses'][] = $there_email;
         $msg['Message']['Subject']['Data'] = $subject;
         $msg['Message']['Subject']['Charset'] = "UTF-8";
         $msg['Message']['Body']['Html']['Data'] = $body;
         $msg['Message']['Body']['Html']['Charset'] = "UTF-8";
         try{
            $result = $this->client->sendEmail($msg);
            $msg_id = $result->get('MessageId');
         } catch (Exception $e) {
            error_log($e->getMessage());
         } 
         return $msg_id;
    }
    
    public function sendOtp($user_id, $bot_id, $app_name, $there_email)
    {
        $otp6 = mt_rand(100000, 999999);
        error_log("Email ".$there_email.",".$app_name);
        $msg_id=0;
        $msg = array();
        $msg['Source'] = "app@1do.in";
        $msg['Destination']['ToAddresses'][] = $there_email;
        $msg['Message']['Subject']['Data'] = "Otp for ".$app_name;
        $msg['Message']['Subject']['Charset'] = "UTF-8";
        $msg['Message']['Body']['Html']['Data'] = "<br/><br/>&emsp;".$otp6." is OTP for validating you email for ".$app_name.
                                     ". Please, do not share this OTP.";
        $msg['Message']['Body']['Html']['Charset'] = "UTF-8";
        $result = $this->client->sendEmail($msg);
        $msg_id = $result->get('MessageId');
        $log = new EmailLog();
                    //$uuid, $user_id, $bot_id, $type, $tid, $my_number, $there_number, $direction, $sms, $ts
        $log->logEmail("", $user_id, $bot_id, $app_name, $msg_id, "", $there_email, 1,  $msg['Message']['Body']['Html']['Data'], time());
        return $otp6;
    }
    
    public function sendTemplateToPerson($user_id, $type, $email_message_id, $Lperson) {
        $SU = new SmsUtils ();
        $SM = new SmsMessage ();
        
        $email = $SM->getEmailMessage ( $user_id, $email_message_id );
        error_log ( "messaging id is=" . $email_message_id );
        error_log ( "Template=" . print_r ( $email, true ) );
        error_log ( "Person=" . print_r ( $Lperson, true ) );
        
        $otp6 = mt_rand ( 100000, 999999 );
        $Lperson ['otp'] = $otp6;
        $subject = $SU->templateReplace ( $email['subject'], $Lperson );
        $body = $SU->templateReplace ( $email['template'], $Lperson );
        $there_email = $Lperson ['email'];
        error_log ( "EMail=body=" . print_r ( $body, true ) ." subject=" . print_r ( $subject, true ) );
        error_log ( "email id=" . $there_email );
        
        $msg_id=0;
        $msg = array();
        $msg['Source'] = "app@1do.in";
        $msg['Destination']['ToAddresses'][] = $there_email;
        $msg['Message']['Subject']['Data'] = $subject;
        $msg['Message']['Subject']['Charset'] = "UTF-8";
        $msg['Message']['Body']['Html']['Data'] = $body;
        $msg['Message']['Body']['Html']['Charset'] = "UTF-8";
        $result = $this->client->sendEmail($msg);
        $msg_id = $result->get('MessageId');
        $log = new EmailLog();
        //$uuid, $user_id, $bot_id, $type, $tid, $my_number, $there_number, $direction, $sms, $ts
        $log->logEmail("", $user_id, $type, "email", $msg_id, "", $there_email, 1,  $msg['Message']['Body']['Html']['Data'], time());
        return $there_email;
    }
    
    public static function sendMicroAppToPerson($user_id, $bot_id, $text, $Lperson) {
        $SU = new SmsUtils ();
        
        $otp6 = mt_rand ( 100000, 999999 );
        $Lperson ['otp'] = $otp6;
        $subject = $SU->templateReplace ( $email['subject'], $Lperson );
        $body = $SU->templateReplace ( $email['body'], $Lperson );
        $there_email = $Lperson ['email'];
        error_log ( "EMail=body=" . print_r ( $body, true ) ." subject=" . print_r ( $subject, true ) );
        error_log ( "email id=" . $there_email );
        
        $msg_id=0;
        $msg = array();
        $msg['Source'] = "app@1do.in";
        $msg['Destination']['ToAddresses'][] = $there_email;
        $msg['Message']['Subject']['Data'] = $subject;
        $msg['Message']['Subject']['Charset'] = "UTF-8";
        $msg['Message']['Body']['Html']['Data'] = $body;
        $msg['Message']['Body']['Html']['Charset'] = "UTF-8";
        $result = $this->client->sendEmail($msg);
        $msg_id = $result->get('MessageId');
        $log = new EmailLog();
            //$uuid, $user_id, $bot_id, $type, $tid, $my_number, $there_number, $direction, $sms, $ts
        $log->logEmail("", $user_id, $type, "email", $msg_id, "", $there_email, 1,  $msg['Message']['Body']['Html']['Data'], time());
        return $there_email;
    }
    
}
?>