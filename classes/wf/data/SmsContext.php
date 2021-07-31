<?php
require_once (__ROOT__ . '/classes/core/Sqlite.php');

class SmsContext extends Sqlite {
    
    private $user_id=null;
    
    public function __construct($user_id, $bot_id) {
        parent::__construct($user_id, $bot_id, self::$UD);
        $this->user_id = $user_id;
        if (!$this->t_exists("user_context")){
            $this->t_crtinsupd("CREATE TABLE IF NOT EXISTS user_context ( number text, sms text, context text, changedOn text, unique(number)) ;");
        }
        if (!$this->t_exists("user_phone")){
            $this->t_crtinsupd("CREATE TABLE IF NOT EXISTS user_phone (  number text, valid text, changedOn text, unique(number)) ;");
        }
        if (!$this->t_exists("user_payment")){
            $this->t_crtinsupd("CREATE TABLE IF NOT EXISTS user_payment (  order_id text, number text, pp_order_id text, pp_pyament_id text, amount text, serviced text, changedOn text, unique(order_id)) ;");
        }
    }

    ////////////////////USER CONTEXT ////////////////////
    
    
    public function saveContext($number, $context, $sms) {
        $sms = $this::esc($sms);
        $context = $this::esc($context);
        $this->t_crtinsupd(sprintf("INSERT OR REPLACE into user_context(number, sms,  context, changedOn) values( '%s', '%s', '%s', '%s') ;", 
                 $number, $sms, $context, time()));
    }
    
    public function getContext($number)
    {
        $res = $this->multiple_rows_cols(sprintf("select context, sms from user_context where number='%s';", $number));
        $res = $res[0]; //first row only
        error_log("Context=".print_r($res, true));
        if (isset($res['context'])){
            return array($res['context'], $res['sms']);
        }
        else {
            return array("", "");
        }
    }
    
    
    public function deleteContext($number)
    {
        $this->t_crtinsupd(sprintf("delete from user_context where number='%s';", $number));
    }
    
    public function expireOldContext()
    {
        $this->t_crtinsupd('delete from user_context where DATE(changedOn) < DATE(NOW() - INTERVAL 1 DAY);');
    }
    
    public function saveNumberValidation($number, $validation){
        $this->t_crtinsupd(sprintf("INSERT OR REPLACE into user_phone(number, valid, changedOn) values( '%s', '%s', '%s') ;",
                $number, $this::esc($validation), time() ));
    }
    public function getNumberValidation($number){
        $res = $this->single_value(sprintf("select valid from user_phone where number='%s';", $number));
        return $res;
    }
    
    public function captureOrder($order_id, $number, $razor_id, $razor_pay_id, $amount){
        $this->t_crtinsupd(sprintf("INSERT OR REPLACE into user_payment(order_id, number, pp_order_id, pp_pyament_id, amount, changedOn) values( '%s', '%s', '%s', '%s', '%s', '%s') ;",
                $order_id, $number, $razor_id, $razor_pay_id, $amount, time() ));
    }
    
    
    public function getLastTransaction($order_id){
        $res = $this->multiple_rows_cols(sprintf("select * from  user_payment where order_id='%s' and serviced is NULL order by changedOn desc limit 1;", $order_id));
        error_log("Res=".print_r($res, true));
        if (isset($res[0])){
            return $res[0]; //single row, return first row
        }
        else {
            return null;
        }
    }
    
}

?>