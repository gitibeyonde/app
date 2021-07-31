<?php
require_once (__ROOT__ . '/classes/core/Mysql.php');


class SmsPayment extends  Mysql {
    
    const RAZORPAY = "RazorPay";
    
    public function __construct() {
        parent::__construct ();
    }
    
    public function getUserData($user_id)
    {
        return $this->selectRow( sprintf ( "select * from users where user_id=%d;" , $user_id) );
    }
    
    public function savePPCreds($user_id, $mName, $mAddress, $mDescription, $ppName, $cred) {
        $ppName = $this->quote($ppName);
        $mName = $this->quote($mName);
        $mAddress = $this->quote($mAddress);
        $mDescription = $this->quote($mDescription);
        $cred = $this->quote($cred);
        $this->changeRow ( sprintf ( "insert into sms_wf_payment(user_id, name, description, address, pp, cred, updated) values( %d, %s, %s,%s, %s, %s, now()) ".
                "on duplicate key update name=%s, address=%s, description=%s, cred=%s"
                , $user_id, $mName, $mDescription, $mAddress, $ppName, $cred, $mName, $mAddress, $mDescription, $cred ) );
    }
    
    public function getPPCreds($user_id, $ppName) {
        $ppName = $this->quote($ppName);
        return $this->selectRow( sprintf ( "select * from sms_wf_payment where user_id=%d and pp=%s;"
                , $user_id, $ppName) );
    }
    
    
    public function deletePPCreds($user_id, $ppName) {
        $bot_id = $this->quote($bot_id);
        $ppName = $this->quote($ppName);
        $this->changeRow( sprintf ( "delete from sms_wf_payment where user_id=%d and  pp=%s;"
                , $user_id, $ppName) );
    }
    
    public function saveTaxation($user_id, $tax1_name, $tax1_percent, $tax2_name, $tax2_percent, $tax3_name, $tax3_percent) {
        $tax1_name = $this->quote($tax1_name);
        $tax2_name = $this->quote($tax2_name);
        $tax3_name = $this->quote($tax3_name);
        error_log("$user_id, $tax1_name, $tax1_percent, $tax2_name, $tax2_percent, $tax3_name, $tax3_percent");
        $tax1_percent = bcdiv($tax1_percent, 100.00, 3);
        $tax2_percent = bcdiv($tax2_percent, 100.00, 3);
        $tax3_percent = bcdiv($tax3_percent, 100.00, 3);
        error_log("$user_id, $tax1_name, $tax1_percent, $tax2_name, $tax2_percent, $tax3_name, $tax3_percent");
                    
        $this->changeRow ( sprintf ( "insert into sms_wf_taxes(user_id, tax1_name, tax1_percent, tax2_name, tax2_percent, tax3_name, tax3_percent, updated) values( %d, %s, %f,%s, %f, %s, %f, now()) ".
                "on duplicate key update tax1_name=%s, tax1_percent=%f, tax2_name=%s, tax2_percent=%f, tax3_name=%s, tax3_percent=%f"
                , $user_id, $tax1_name, $tax1_percent, $tax2_name, $tax2_percent, $tax3_name, $tax3_percent, $tax1_name, $tax1_percent, $tax2_name, $tax2_percent, $tax3_name, $tax3_percent) );
    }
    
    public function getTaxation($user_id) {
        return $this->selectRow( sprintf ( "select * from sms_wf_taxes where user_id=%d;", $user_id) );
    }
    
}

?>