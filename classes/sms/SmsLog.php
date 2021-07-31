<?php
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/core/Mysql.php');

class SmsLog extends MySql
{
    
    public function __construct() {
        parent::__construct ();
    }
    
    public function logSms($uuid, $user_id, $bot_id, $type, $tid, $my_number, $there_number, $direction, $sms, $ts) ###RETURN LAST INSERT ID
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('insert into sms_log(uuid, user_id, bot_id, type, tid, my_number, there_number,  direction, sms, changedOn) '.
                    'values(:uuid, :user_id, :bot_id, :type, :tid, :my_number, :there_number, :direction, :sms, now())');
            $sth->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->bindValue(':type', $type, PDO::PARAM_STR);
            $sth->bindValue(':tid', $tid, PDO::PARAM_STR);
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->bindValue(':there_number', $there_number, PDO::PARAM_STR);
            $sth->bindValue(':direction', $direction, PDO::PARAM_STR);
            $sth->bindValue(':sms', $sms, PDO::PARAM_STR);
            $sth->execute();
            error_log("logSms Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
            return $this->db_connection->lastInsertId();
        }
    }
    
    public function getSMSLogTriggerForMonth($user_id){
        $date = $this->quote(date('Y-m-01')." 00:00:00");
        return $this->selectRows ( sprintf ( 'select * from sms_log where user_id=%d and changedOn>=%s and type="trig"', $user_id, $date) );
    }
    
    public function getSMSLogSurveyForMonth($user_id){
        $date = $this->quote(date('Y-m-01')." 00:00:00");
        return $this->selectRows ( sprintf ( 'select * from sms_log where user_id=%d and changedOn>=%s and type="surv"', $user_id, $date) );
    }
    
    public function getSMSLogTriggerCountForMonth($user_id){
        $date = $this->quote(date('Y-m-01')." 00:00:00");
        return intval($this->selectOne ( sprintf ( 'select count(*) from sms_log where user_id=%d and changedOn>=%s and type="trig"', $user_id, $date) ));
    }
    
    public function getSMSLogSurveyCountForMonth($user_id){
        $date = $this->quote(date('Y-m-01')." 00:00:00");
        return intval($this->selectOne ( sprintf ( 'select count(*) from sms_log where user_id=%d and changedOn>=%s and type="surv"', $user_id, $date) ));
    }
    public function getSmsLogForBot($bot_id){
        $stmt = array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from sms_log where bot_id=:bot_id');
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->execute();
            while($obj =  $sth->fetch()){
                $stmt[]=$obj;
            }
            error_log("getSmsLog Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    
    public function getSmsLogForMyNumber($my_number){
        $stmt = array();
        if ($this->databaseConnection()) {
            $smsutils = new SmsUtils();
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from sms_log where my_number=:my_number and changedOn > :changedOn order by changedOn desc');
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->bindValue(':changedOn', $smsutils->getOneWeekBack(), PDO::PARAM_STR);
            //$sth->bindValue(':direction', 1, PDO::PARAM_STR);
            $sth->execute();
            while($obj =  $sth->fetch()){
                $stmt[]=$obj;
            }
            error_log("getSmsLogForMyNumber Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    
    public function getMesssageRepliesForMyNumber($my_number){
        $stmt = array();
        $last_interactions = array();
        if ($this->databaseConnection()) {
            $smsutils = new SmsUtils();
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from sms_log where my_number=:my_number and changedOn > :changedOn order by changedOn desc');
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->bindValue(':changedOn', $smsutils->getOneWeekBack(), PDO::PARAM_STR);
            $sth->execute();
            while($obj =  $sth->fetch()){
                if ($obj['type'] == 'surv'){
                    $last_interactions[$obj['there_number']] = $obj;
                    error_log($obj['direction']."Survey/Trigger ".$obj['sms']);
                }
                if ($obj['direction'] == 1) {
                    foreach($last_interactions as $num=>$mobj){
                        if ($num == $obj['there_number']){
                            $stmt[]=$mobj;
                            $obj['tid'] = $mobj['tid'];
                            $stmt[]=$obj;
                            unset($last_interactions[$num]);
                            continue;
                        }
                    }
                }
            }
            error_log("getChatLogForMyNumber Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    
    public function getChatLogForMyNumber($my_number, $hide){
        $stmt = array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select l.* from sms_log l inner join (select there_number, max(changedOn) as latest from sms_log where my_number=:my_number '
                    .' group by there_number) r on l.changedOn = r.latest order by changedOn desc limit 100;');
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->execute();
            while($obj =  $sth->fetch()){
                if ($hide){
                    if (strlen($obj["there_number"])>10){
                        $stmt[]=$obj;
                    }
                }
                else {
                    $stmt[]=$obj;
                }
            }
            error_log("getChatLogForMyNumber Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    
    public function getChatLogForMyNumberThereNumber($my_number, $there_number){
        $stmt = array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from sms_log where my_number=:my_number and there_number=:there_number order by changedOn desc limit 100;');
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->bindValue(':there_number', $there_number, PDO::PARAM_STR);
            $sth->execute();
            //error_log("my number = $my_number there number = $there_number");
            while($obj =  $sth->fetch()){
                $stmt[]=$obj;
                //error_log("query object is ".print_r($obj, true));
            }
            //error_log("getChatLogForMyNumberThereNumber Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    public function getChatLogForMyNumber_DEPRICATED($my_number){
        $stmt = array();
        $last_interactions = array(); 
        if ($this->databaseConnection()) {
            $smsutils = new SmsUtils();
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select l.* from sms_log l inner join (select there_number, max(changedOn) as latest from sms_log where my_number=:my_number '
                    .' group by there_number) r on l.changedOn = r.latest order by changedOn desc limit 100;');
            //$sth = $this->db_connection->prepare('select * from sms_log where my_number=:my_number order by changedOn desc, there_number asc limit 100;');
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->execute();
            while($obj =  $sth->fetch()){
                if ($obj['type'] == 'surv' || $obj['type'] == 'trig'){
                    $last_interactions[$obj['there_number']] = $obj['there_number'];
                    //error_log($obj['direction']."Survey/Trigger ".$last_interactions[$obj['there_number']].$obj['sms']);
                }
                else {
                    if ($obj['direction'] == 1) {
                        foreach($last_interactions as $key=>$num){
                            if ($num == $obj['there_number']){
                                unset($last_interactions[$obj['there_number']]);
                                //error_log("Skipping ".$obj['sms']);
                                continue;
                            }
                        }
                        $stmt[]=$obj;
                    }
                    else {
                        $stmt[]=$obj;
                    }
                }
            }
            error_log("getChatLogForMyNumber Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    
    public function getSmsLog($user_id, $tid){
        $stmt = array();
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select * from sms_log where user_id=:user_id and tid=:tid');
            $sth->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $sth->bindValue(':tid', $tid, PDO::PARAM_STR);
            $sth->execute();
            while($obj =  $sth->fetch()){
                $stmt[]=$obj;
            }
            error_log("getSmsLog Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
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
    
    public function getSentCountSinceMidnight($number){
        $stmt = 0;
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select count(*) from sms_log where direction=0 and my_number=:number and changedOn > DATE_FORMAT(now(), "%Y-%m-%d 00:00:00")');
            $sth->bindValue(':number', $number, PDO::PARAM_STR);
            $sth->execute();
            $stmt =  $sth->fetch()[0];
            error_log("getSentCountSinceMidnight Error=" . implode(",", $sth->errorInfo()).print_r($stmt, true));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return intval($stmt);
    }
    
    
    public function getLastSurveySent($my_number, $there_number){
        $stmt = null;
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select changedOn from sms_log where my_number=:my_number and there_number=:there_number order by changedOn desc limit 1');
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->bindValue(':there_number', $there_number, PDO::PARAM_STR);
            $sth->execute();
            $stmt=$sth->fetch()[0];
            error_log("getLastSurveySent Error=" . implode(",", $sth->errorInfo()).print_r($stmt, true));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    
    
    public function getOtpNumberSentInLast10Mins($there_number){
        if ($this->databaseConnection()) {
            $start = date(DateTime::ATOM);
            $start = date(DateTime::ATOM,strtotime('-10 minutes',strtotime($start)));
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select count(*) from sms_log where there_number=:there_number and sms like '.
                    ' "%OTP for validating your phone number%" and direction=0 and changedOn > :changedOn order by changedOn asc');
            $sth->bindValue(':there_number', substr($there_number, 1), PDO::PARAM_STR);
            $sth->bindValue(':changedOn',  date(DateTime::ATOM,strtotime('-10 minutes',strtotime($start))), PDO::PARAM_STR);
            $sth->execute();
            error_log("getOtpNumberSentInLast10Mins Error=" . implode(",", $sth->errorInfo()).$start);
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
            return $sth->fetch()[0];
        }
        
    }
    
    public function getResponse($my_number, $there_number, $from){
        $stmt = array();
        if ($this->databaseConnection()) {
            $sth = $this->db_connection->prepare('select * from sms_log where my_number=:my_number and there_number=:there_number and changedOn > :changedOn order by changedOn asc limit 10');
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->bindValue(':there_number', $there_number, PDO::PARAM_STR);
            $sth->bindValue(':changedOn',  $from, PDO::PARAM_STR);
            $sth->execute();
            while($obj =  $sth->fetch()){
                error_log("getResponse=".$from.$obj['type']);
                if ($obj['type'] != "resp") {
                    break;
                }
                else{
                    $stmt[]=$obj;
                }
            }
            error_log("getResponse Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    
    public function respondToMessages($my_number, $there_number){
        if ($this->databaseConnection()) {
            $sth = $this->db_connection->prepare('select type, direction from sms_log where there_number=:there_number and my_number=:my_number order by changedOn desc limit 1;');
            $sth->bindValue(':my_number', $my_number, PDO::PARAM_STR);
            $sth->bindValue(':there_number', $there_number, PDO::PARAM_STR);
            $sth->execute();
            $obj =  $sth->fetch();
            error_log("getResponse=".print_r($obj, true));
            if ($obj['direction'] == 0 && ($obj['type'] == 'surv' || $obj['type'] == 'trig') ){
                error_log("Survey or Trig were the last message sent");
                return "0";
            }
            else {
                error_log("No Survey or Trig were sent");
                return "1";
            }
            error_log("respondToMessages Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return "1";
    }
    
    ///////////////////GSM HEALTH////////////////////////
    
    public function getGsmHealth($uuid, $from){
        $stmt = array();
        if ($this->databaseConnection()) {
            $date = Utils::$dt->setTimeStamp($from);
            // database query, getting all the info of the selected usersms_log
            $sth = $this->db_connection->prepare('select * from  gsm_health where uuid=:uuid and changedOn > :time order by changedOn desc');
            $sth->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $sth->bindValue(':time', $date->format(DateTime::ATOM), PDO::PARAM_STR);
            $sth->execute();
            while($obj =  $sth->fetch()){
                $stmt[]=$obj;
            }
            //error_log("getGsmHealth Error=" . implode(",", $sth->errorInfo()).print_r($stmt, true));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
        return $stmt;
    }
    
    public function pingGsmHealth($uuid, $type, $alive)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('insert into gsm_health(uuid, type, alive, changedOn) '.
                    'values(:uuid, :type, :alive, now())');
            $sth->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $sth->bindValue(':type', $type, PDO::PARAM_STR);
            $sth->bindValue(':alive', $alive, PDO::PARAM_STR);
            $sth->execute();
            //error_log("pingGsmHealth Error=" . implode(",", $sth->errorInfo()));
            if ( $sth->errorInfo()[0] != "0000"){
                $_SESSION['message'] = print_r($sth->errorInfo(), true);
            }
        }
    }
    
}