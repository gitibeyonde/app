<?php
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/sms/SmsLog.php');
require_once (__ROOT__ . '/classes/sms/SmsMessage.php');
require_once (__ROOT__ . '/classes/sms/AwsSnsSms.php');
class SmsSend {
    public function SmsSend() {
    }
    public static function sendTemplateToPerson($user_id, $type, $message_id, $Lperson) {
        $SU = new SmsUtils ();
        $SM = new SmsMessage ();
        
        $template = $SM->getMessage ( $user_id, $message_id ) ['template'];
        error_log ( "messaging id is=" . $message_id );
        error_log ( "Template=" . print_r ( $template, true ) );
        error_log ( "Person=" . print_r ( $Lperson, true ) );
        
        $otp6 = mt_rand ( 100000, 999999 );
        $Lperson ['otp'] = $otp6;
        $sms = $SU->templateReplace ( $template, $Lperson );
        $phone = $Lperson ['number'];
        error_log ( "SMS=" . print_r ( $sms, true ) );
        error_log ( "Phone=" . $phone );
        
        if (substr ( $phone, 0, 1 ) != "+") {
            $phone = "+" . $phone;
        }
        $Sns = new AwsSnsSms ();
        $Sns->sendSms ($phone, $sms);
        $log = new SmsLog();
        $log->logSms("", $user_id, $type, $type, $message_id, "", $phone, 1, $sms, time());
        if ($SU->isOtpRequired ( $sms )) {
            return $phone . ":" . $otp6 . "\n";
        } else {
            return $phone . ":" . $sms . "\n";
        }
    }
    public static function sendMicroAppToPerson($user_id, $bot_id, $text, $Lperson) {
        $SU = new SmsUtils ();
        $SM = new SmsMessage ();
        $otp6 = mt_rand ( 100000, 999999 );
        $Lperson ['otp'] = $otp6;
        $sms = $SU->templateReplace ( $text, $Lperson );
        $phone = $Lperson ['number'];
        error_log ( "SMS=" . print_r ( $sms, true ) );
        error_log ( "Phone=" . $phone );
        
        if (substr ( $phone, 0, 1 ) != "+") {
            $phone = "+" . $phone;
        }
        $Sns = new AwsSnsSms ();
        $Sns->sendSms ($phone, $sms);
        $log = new SmsLog();
                //$uuid, $user_id, $bot_id, $type, $tid, $my_number, $there_number, $direction, $sms, $ts
        $log->logSms("", $user_id, $bot_id, "app", 0, "", $phone, 1, $sms, time());
        
        if ($SU->isOtpRequired ( $sms )) {
            return $phone . ":" . $otp6 . "\n";
        } else {
            return $phone . ":" . $sms . "\n";
        }
    }
    public static function sendOTP($user_id, $bot_id, $app_name, $phone) {
        if ($phone != null) {
            if (substr ( $phone, 0, 1 ) != "+") {
                $phone = "+" . $phone;
            }
            $otp6 = mt_rand ( 100000, 999999 );
            $sms = $otp6 . " is the OTP for validating the phone number for " . $app_name . ". OTPs are SECRET, please do not share  !";
            $Sns = new AwsSnsSms ();
            $Sns->sendSms ($phone, $sms);
            $log = new SmsLog();
                    //$uuid, $user_id, $bot_id, $type, $tid, $my_number, $there_number, $direction, $sms, $ts
            $log->logSms("", $user_id, $bot_id, $app_name, 0, "", $phone, 1, $sms, time());
            return $otp6;
        } else {
            return "ERROR: OTP Bad phone number :" . $phone;
        }
    }
    public static function templateReplace($template, $data) {
        if (preg_match_all ( "/{{(.*?)}}/", $template, $m )) {
            foreach ( $m [1] as $i => $varname ) {
                $template = str_replace ( $m [0] [$i], sprintf ( '%s', $data [$varname] ), $template );
            }
        }
        return $template;
    }
}

?>