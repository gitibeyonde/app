<?php
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/checks/Check.php');

class CheckAppointment extends Check {
    
    
    protected $_appointment_day_start = array("1", "Day From", "Appointment Given From", "Please choose a date from tomorrow.");
    protected $_appointment_day_end = array("10", "Day Till", "Appointment Given Till", "Please choose a date within next 10 days;");
    
    protected $_appointment_time_start = array("09:00", "Open Time", "Appointment Start Time in format 11:11", "Choose office time, after 9am;");
    protected $_appointment_time_end = array("17:00", "CLose Time", "Appointment End Time in format 00:00", "Choose a earlier time, before 5pm;");
    
    protected $_appointment_week = array("6", "Working Days", "5 stands for 5 day working week.", "Offices are closed on this day;");
    
    protected $_appointment_duration = array("60", "Appointment Duration", "Appointment duration in minutes.", "The appointment slot is already taken, please choose a different time;");
    
    
    function __construct(){
        parent::__construct();
    }
    
    public function range($value, $name){
        if ($name == "appointment"){ 
            //CHECK TIME 2020-04-29T15:00:00+00:00
            $hrmin = substr($value,11, 5);
            $r1 = $this->_utils->range($hrmin, "number", $this->_appointment_time_start, $this->_appointment_time_end);
            // CHECK DAY RANGE
            $dt = DateTime::createFromFormat(DateTime::ATOM, $value);
            $r2 = $this->_utils->range($dt, "datetime", $this->_appointment_day_start, $this->_appointment_day_end);
            //CHECK WORKING DAY
            $day =  $dt->format('N'); // range from 1-7
            $r3 = $this->_utils->range($day, "number", array(0, "", "", ""), $this->_appointment_week);
            
            return trim($r1.$r2.$r3);
        }
        else {
            throw new Exception("Unknow check type passed to CheckAppointment ".$type);
        }
    }
    
    public function getAppointmentsCountInTimeSlot($bot_id, $drname, $date_atom, $appointment_slot){
        $ts = strtotime($date_atom);
        $start_time = new DateTime();//"2019-12-03T15:00:00+05:30"
        $start_time->setTimestamp(strtotime("-".$appointment_slot." minutes", $ts));
        $end_time = new DateTime();//"2019-12-03T19:00:00+05:30"
        $end_time->setTimestamp(strtotime("+".$appointment_slot." minutes", $ts));
        $this->_log->debug("start time = ".$start_time->format(DateTime::ATOM)." and end time = ".$end_time->format(DateTime::ATOM));
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select count(*) from sms_user_data a, sms_user_data b where a.name="drname" and '
                    . 'a.value=:drname and a.number = b.number and b.name="appointment" and '
                    . 'b.value > :start_time and b.value < :end_time and a.bot_id=b.bot_id and a.bot_id=:bot_id;'
                    );
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->bindValue(':drname', $drname, PDO::PARAM_STR);
            $sth->bindValue(':start_time', $start_time->format(DateTime::ATOM), PDO::PARAM_STR);
            $sth->bindValue(':end_time', $end_time->format(DateTime::ATOM), PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetch()[0];
        }
        return 0;
    }
    
    
    
}

?>