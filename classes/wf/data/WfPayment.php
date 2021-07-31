<?php
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/core/Mysql.php');
include_once(__ROOT__ . '/classes/sms/SmsLog.php');
include_once(__ROOT__ . '/classes/sms/EmailLog.php');

class WfPayment extends Mysql
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function addPaymentProcessor($user_id, $bot_id, $ppname, $cred){
        $this->changeRow ( sprintf ( "insert into sms_wf_payment values( %d, '%s', '%s', '%s', now());",
                $user_id, $bot_id, $ppname, $cred) );
    }
    
    
    public function addOrder($user_id, $type, $my_number, $amount)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('insert into gsm_order(user_id, type, my_number, amount, status, changedOn) '.
                    'values(:user_id, :type, :my_number, :amount, "init", now())');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->bindValue(':type', $type, PDO::PARAM_STR);
            $sth->bindValue(':amount', $amount, PDO::PARAM_STR);
            $sth->execute();
            error_log("addOrder Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
            return $this->db_connection->lastInsertId();
        }
    }
    public function updateVenderOrderId($user_id, $order_id, $vendor_order_id){
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('update gsm_order set status="strt", vendor_order_id=:vendor_order_id where user_id=:user_id and id=:order_id');
            $sth->bindValue(':vendor_order_id', $vendor_order_id, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':order_id', $order_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("completeOrder Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    public function captureOrder($user_id, $order_id, $vendor_payment_id){
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('update gsm_order set status="capt", vendor_payment_id=:vendor_payment_id where user_id=:user_id and id=:order_id');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':order_id', $order_id, PDO::PARAM_STR);
            $sth->bindValue(':vendor_payment_id', $vendor_payment_id, PDO::PARAM_STR);
            $sth->execute();
            error_log("completeOrder Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
    
    public function getOrder($user_id, $order_id){
        $order = array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from gsm_order where user_id=:user_id and id=:order_id');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':order_id', $order_id, PDO::PARAM_STR);
            $sth->execute();
            while($obj =  $sth->fetch()){
                $order[]=$obj;
            }
            //error_log("getJob Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $order;
    }
    
    public function getThisMonthDeposit($user_id){
        $date = $this->quote(date('Y-m-01')." 00:00:00");
        return $this->selectOne(sprintf('select SUM(amount) from gsm_order where user_id=%d and status="capt" and changedOn >= %s', $user_id, $date));
    }
    
    public function getThisMonthExpenses($user_id){
        $EM = new EmailLog();
        $SM = new SmsLog();
        
        $email_count = $EM->getEmailLogCountForMonth($user_id);
        $trigger_count = $SM->getSMSLogTriggerCountForMonth($user_id);
        $survey_count = $SM->getSMSLogSurveyCountForMonth($user_id); 
        return ($survey_count * 0.25 + $trigger_count * 0.50 + $email_count * 0.25);
    }
    
    public function getThisMonthBalance($user_id){
        $deposit = $this->getThisMonthDeposit($user_id);
        $expense = $this->getThisMonthExpenses($user_id);
        return $deposit - $expense;
    }
    
}