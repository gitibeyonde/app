<?php 

class SmsBotParams
{
    // working day, appointment time,
    private $db_connection = null;
    
    public function __construct()
    {
    }
    private function databaseConnection()
    {
        // if connection already exists
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                $this->db_connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $_SESSION['message']  = MESSAGE_DATABASE_ERROR . $e->getMessage();
            }
        }
        return false;
    }
    
    public static $max_minified_url=5;
    public static $category = array("general", "company", "healthcare", "hospital", "salon", "visitor");
    public static $params = array(
            "healthcare" => array("day_start", "lunch_start", "lunch_end", "day_end", "appointment_duration", "work_week", "appointment_day_range", "age_start", "age_end"),
            "hospital" => array("day_start", "lunch_start", "lunch_end", "day_end", "appointment_duration", "work_week", "appointment_day_range", "age_start", "age_end"),
            "salon" => array("day_start", "lunch_start", "lunch_end", "day_end", "appointment_duration", "work_week", "appointment_day_range", "age_start", "age_end"),
            "company" => array("day_start", "lunch_start", "lunch_end", "day_end", "work_week"), 
            "visitor" => array("day_start", "lunch_start", "lunch_end", "day_end", "work_week", "appointment_day_range", "appointment_duration"), 
            "general" => array());
    public static $errors = array(
            "healthcare" => array("bad_date_range", "bad_time_range", "bad_date_format", "bad_work_week", "bad_age_range", "fully_booked", "error_exceeded"),
            "hospital" => array("bad_date_range", "bad_time_range", "bad_date_format", "bad_work_week", "bad_age_range", "fully_booked", "error_exceeded"),
            "salon" => array("bad_date_range", "bad_time_range", "bad_date_format", "bad_work_week", "bad_age_range", "error_exceeded"),
            "company" => array(),
            "visitor" => array(),
            "general" => array());

    public static $reminders = array("feedback"=>array("1 day", "Please rate your overall experience and if you have comments write them too. This will help make SO Hospital better. e.g.  5, <comments>"),
            "reminder"=>array("6 month", "Please, revisit SO Helathcare for your scheduled checkup. Let me know if you need an appointment.")
    );
    public static $healthcare_speciliaties = array("ALLERGY & IMMUNOLOGY", "ANESTHESIOLOGY", "DERMATOLOGY", "DIAGNOSTIC RADIOLOGY", "EMERGENCY MEDICINE",
            "FAMILY MEDICINE","INTERNAL MEDICINE","GASTROENTEROLOGY", "HEMATOLOGY", "NEPHROLOGY", "ONCOLOGY", "RHEUMATOLOGY", "MEDICAL GENETICS","NEUROLOGY",
            "NUCLEAR MEDICINE","OBSTETRICS & GYNECOLOGY","OPHTHALMOLOGY", "PATHOLOGY","PEDIATRICS","PHYSICAL MEDICINE & REHABILITATION",
            "PREVENTIVE MEDICINE","PSYCHIATRY","RADIATION ONCOLOGY","SURGERY", "UROLOGY", "CARDIOLOGY"
    );
    public static $hospital_speciliaties = array("ALLERGY & IMMUNOLOGY", "ANESTHESIOLOGY", "DERMATOLOGY", "DIAGNOSTIC RADIOLOGY", "EMERGENCY MEDICINE",
            "FAMILY MEDICINE","INTERNAL MEDICINE","GASTROENTEROLOGY", "HEMATOLOGY", "NEPHROLOGY", "ONCOLOGY", "RHEUMATOLOGY", "MEDICAL GENETICS","NEUROLOGY",
            "NUCLEAR MEDICINE","OBSTETRICS & GYNECOLOGY","OPHTHALMOLOGY", "PATHOLOGY","PEDIATRICS","PHYSICAL MEDICINE & REHABILITATION",
            "PREVENTIVE MEDICINE","PSYCHIATRY","RADIATION ONCOLOGY","SURGERY", "UROLOGY", "CARDIOLOGY"
    );
    public static $appointment_data_name = array("username", "age", "drname", "appointment");
    public static $visitor_data_name = array("username", "resname", "visit");
    
    public static function checkTimeRange($date, $bot_id, $category){//Y-m-d\TH:i:sP
        $params = new SmsBotParams();
        $min = date('H:i', strtotime($params->getBotParam($bot_id, $category, "day_start")));
        $max = date('H:i', strtotime($params->getBotParam($bot_id, $category, "day_end")));
        $time =  $date->format('H:i'); 
        if( $time < $max && $time > $min){
            error_log("GOOD TIME RANGE ".$min."<".$time."<".$max);
            return array(True, null, null);
        }
        else {
            error_log("BAD TIME RANGE ".$min."<".$time."<".$max);
            return array(False, $min, $max);
        }
    }
    
    public static function checkAppointmentDayRange($date_atom, $bot_id, $category){//Y-m-d\TH:i:sP
        $params = new SmsBotParams();
        $appointment_day_range= $params->getBotParam($bot_id, $category, "appointment_day_range");
        $max_date = strtotime('+'.$appointment_day_range.' day');
        error_log("TIME MAX ".$max_date);
        $date = strtotime($date_atom);
        error_log("TIME GIVEN".$date);
        error_log("TIME TODAY".time());
        if( $date < $max_date && $date > time()){
            error_log("GOOD DATE RANGE ".time()."<".$date."<".$max_date);
            return array(True, $appointment_day_range);
        }
        else {
            error_log("BAD DATE RANGE ".time()."<".$date."<".$max_date);
            return array(False, $appointment_day_range);
        }
    }
    
    
    public static function checkWeekday($date, $bot_id, $category){
        $params = new SmsBotParams();
        $ww = $params->getBotParam($bot_id, $category, "work_week");
        $day =  $date->format('w');
        if($day < $ww){
            error_log("weekday ".$day."<".$ww);
            return array(True, $ww);
        }
        else {
            error_log("not in working weekday ".$day."<".$ww);
            return array(False, $ww);
        }
    }
    
    public static function checkAgeRange($age, $bot_id, $category){
        $params = new SmsBotParams();
        $min = $params->getBotParam($bot_id, $category, "age_start");
        $max = $params->getBotParam($bot_id, $category, "age_end");
        if($age < $max && $age > $min){
            error_log("IN AGE RANGE ".$min."<".$age."<".$max);
            return array(True, $min, $max);
        }
        else {
            error_log("NOT AGE RANGE ".$min."<".$age."<".$max);
            return array(False, $min, $max);
        }
    }
    
    
    public function error($user_id, $bot_id, $category, $error_name){
        $error_str = $this->getBotError($bot_id, $category, $error_name);
        error_log("Error str $error_str");
        if (preg_match_all("/{{(.*?)}}/", $error_str, $m)) {
            foreach ($m[1] as $i => $field) {
                error_log($i."Field = " . $field);
                $names=explode("/", $field);
                $dt = $names[0];
                $dn = $names[1];
                if ($dt == "param"){
                    $value = $this->getBotParam($bot_id, $category, $dn);
                    error_log("Value = $value  and Name = ".$dn);
                    $pattern = "/{{param\/".$dn."}}/";
                    $error_str = preg_replace($pattern, $value, $error_str);
                }
            }
        }
        return $error_str;
    }
    
    ////////////////////////// PARAMS and ERRORS /////////////////////////////////
    
    public function saveBotParam($bot_id, $category, $name, $value){
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'insert into sms_bot_params(bot_id, category, name, value, changedOn) ' .
                    'values(:bot_id, :category, :name, :value, now()) ' .
                    'ON DUPLICATE KEY UPDATE value = :value;' );
            $sth->bindValue ( ':bot_id', $bot_id, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->bindValue ( ':name', $name, PDO::PARAM_STR );
            $sth->bindValue ( ':value', $value, PDO::PARAM_STR );
            $sth->execute ();
        }
    }
    
    
    public function getBotParam($bot_id, $category, $name)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select value from sms_bot_params where bot_id=:bot_id and category=:category and name=:name');
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->bindValue(':category', $category, PDO::PARAM_STR);
            $sth->bindValue(':name', $name, PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetch()[0];
        }
    }
    
    public function saveBotError($bot_id, $category, $name, $value){
        if ($this->databaseConnection ()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare ( 'insert into sms_bot_errors(bot_id, category, name, value, changedOn) ' .
                    'values(:bot_id, :category, :name, :value, now()) ' .
                    'ON DUPLICATE KEY UPDATE value = :value;' );
            $sth->bindValue ( ':bot_id', $bot_id, PDO::PARAM_STR );
            $sth->bindValue ( ':category', $category, PDO::PARAM_STR );
            $sth->bindValue ( ':name', $name, PDO::PARAM_STR );
            $sth->bindValue ( ':value', $value, PDO::PARAM_STR );
            $sth->execute ();
        }
    }
    
    
    public function getBotError($bot_id, $category, $name)
    {
        if ($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $sth = $this->db_connection->prepare('select value from sms_bot_errors where bot_id=:bot_id and category=:category and name=:name');
            $sth->bindValue(':bot_id', $bot_id, PDO::PARAM_STR);
            $sth->bindValue(':category', $category, PDO::PARAM_STR);
            $sth->bindValue(':name', $name, PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetch()[0];
        }
    }
}

?>


