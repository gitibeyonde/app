<?php
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/core/Mysql.php');

class EmailLog extends Mysql 
{
    
    public function __construct() {
        parent::__construct ();
    }
    
    
    public function logEmail($uuid, $user_id, $bot_id, $type, $tid, $my_email, $there_email, $direction, $email, $ts) ###RETURN LAST INSERT ID
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('insert into email_log(uuid, user_id, bot_id, type, tid, my_email, there_email,  direction, email, changedOn) '.
                    'values(:uuid, :user_id, :bot_id, :type, :tid, :my_email, :there_email, :direction, :email, now())');
            $sth->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $sth->bindValue(':my_email', $my_email, PDO::PARAM_STR);
            $sth->bindValue(':type', $type, PDO::PARAM_STR);
            $sth->bindValue(':tid', $tid, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->bindValue(':there_email', $there_email, PDO::PARAM_STR);
            $sth->bindValue(':direction', $direction, PDO::PARAM_STR);
            $sth->bindValue(':email', $email, PDO::PARAM_STR);
            $sth->execute();
            error_log("logSms Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
            return $this->db_connection->lastInsertId();
        }
    }
    
    
    public function getEmailLogForMonth($user_id){
        $date = $this->quote(date('Y-m-01')." 00:00:00");
        return $this->selectRows ( sprintf ( 'select * from email_log where user_id=%d and changedOn>=%s', $user_id, $date) );
    }
    
    public function getEmailLogCountForMonth($user_id){
        $date = $this->quote(date('Y-m-01')." 00:00:00");
        return intval($this->selectOne ( sprintf ( 'select count(*) from email_log where user_id=%d and changedOn>=%s', $user_id, $date) ));
    }
    
    
    public function getSmsLogCount($user_id, $tid){
        $stmt = 0;
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select count(*) from sms_log where user_id=:user_id and tid=:tid');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':tid', $tid, PDO::PARAM_STR);
            $sth->execute();
            $stmt =  $sth->fetch()[0];
            error_log("getSmsLogCount Error=" . implode(",", $sth->errorInfo()).print_r($stmt, true));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return intval($stmt);
    }
    
}