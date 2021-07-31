<?php
// include the config
require_once (__ROOT__ . '/classes/core/Sqlite.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
class WfUserData extends Sqlite {
    private $user_id = null;
    
    // User id is the first param
    public function __construct($uid, $bid) {
        parent::__construct ( $uid, $bid, self::$UD );
        $this->user_id = $uid;
        if (! $this->t_exists ( "user_data" )) {
            $this->t_crtinsupd ( "CREATE TABLE IF NOT EXISTS user_data ( number text, name text, value text, changedOn text, unique(number, name)) ;" );
        }
        if (! $this->t_exists ( "user_data_report" )) {
            $this->t_crtinsupd ( "CREATE TABLE IF NOT EXISTS user_data_report ( name text, cols text, changedOn text, unique(name)) ;" );
        }
        if (! $this->t_exists ( "user_data_archive" )) {
            $this->t_crtinsupd ( "CREATE TABLE IF NOT EXISTS user_data_archive ( number text, name text, value text, changedOn text, unique(number, name)) ;" );
        }
    }
    public static function down($a, $x) {
        if (count ( $a ) - 1 > $x) {
            $b = array_slice ( $a, 0, $x, true );
            $b [] = $a [$x + 1];
            $b [] = $a [$x];
            $b += array_slice ( $a, $x + 2, count ( $a ), true );
            return ($b);
        } else {
            return $a;
        }
    }
    public static function up($a, $x) {
        if ($x > 0 and $x < count ( $a )) {
            $b = array_slice ( $a, 0, ($x - 1), true );
            $b [] = $a [$x];
            $b [] = $a [$x - 1];
            $b += array_slice ( $a, ($x + 1), count ( $a ), true );
            return ($b);
        } else {
            return $a;
        }
    }
    public function saveReportFormat($name, $cols) {
        $col_str = implode ( ",", $cols );
        $col_str = self::esc ( $col_str );
        $this->t_crtinsupd ( sprintf ( "insert or replace into user_data_report(name, cols, changedOn) values( '%s', '%s', datetime('now'));", $name, $col_str ) );
    }
    public function getReportFormat($name) {
        return explode ( ",", $this->single_value ( sprintf ( "select cols from user_data_report where name='%s'", $name ) ) );
    }
    public function deleteReportFormat($name) {
        $this->t_crtinsupd ( sprintf ( "delete from user_data_report where name='%s'", $name ) );
    }
    public function saveUserData($number, $name, $value) {
        if ($number != null && $name != null && (strlen($number) < 4)  && (strlen($name) == 0) ) {
            return;
        }
        $value = self::esc ( $value );
        $this->t_crtinsupd ( sprintf ( "insert or replace into user_data(number, name, value, changedOn) values( '%s', '%s', '%s', datetime('now'));", $number, $name, $value ) );
    }
    public function archiveUserData($number) {
        $Lud = $this->getAllUserData($number);
        $number = $number.date('.yMd.H.i'); // add datetime to the number before arcgiving
        foreach ($Lud as $row) {
            $name = $row['name'];
            $value = self::esc ( $row['value'] );
            $this->t_crtinsupd ( sprintf ( "insert or replace into user_data_archive(number, name, value, changedOn) values( '%s', '%s', '%s', datetime('now'));",
                    $number, $name, $value ) );
        }
    }
    public function getUserData($number, $name, $field = null) {
        if ($field != null){
            return $this->single_value ( sprintf ( "select %s from user_data where number=%d and name='%s'", $field, $number, $name ) );
        }
        else {
            return $this->single_value ( sprintf ( "select value from user_data where number=%d and name='%s'", $number, $name ) );
        }
    }
    public function getRandomUserDataForTest($name) {
        return $this->single_value ( sprintf ( "select value from user_data where name='%s' limit 1", $name ) );
    }
    public function getAllUserData($number) {
        return $this->multiple_rows_cols ( sprintf ( "select rowid, name, value, changedOn from user_data where number='%s'", $number ) );
    }
    public function getAllData() {
        return $this->multiple_rows_cols ( sprintf ( "select * from user_data order by number;" ) );
    }
    public function deleteUserData($number) {
        $number = self::esc ( $number);
        $this->t_crtinsupd ( sprintf ( "delete from user_data where number='%s';", $number ) );
    }
    public function deleteUserNameValue($number, $name) {
        $this->t_crtinsupd ( sprintf ( "delete from user_data where number=%d and name='%s';", $number, $name ) );
    }
    public function incrErrorCount($bot_id, $number) {
        if ($bot_id == null) {
            throw new Exception ( "Bot id cannot be null" );
        }
        $cnt = 0;
        if ($this->databaseConnection ()) {
            $cnt = $this->getUserData ( $bot_id, $number, "error" );
            $cnt = $cnt + 1;
            $this->saveUserData ( $bot_id, $number, "error", $cnt );
        }
        return $cnt;
    }
    public function resetErrorCount($bot_id, $number) {
        if ($bot_id == null) {
            throw new Exception ( "Bot id cannot be null" );
        }
        $cnt = 0;
        if ($this->databaseConnection ()) {
            $this->saveUserData ( $bot_id, $number, "error", $cnt );
        }
        return $cnt;
    }
    public function generateReport() {
        $cols = array ();
        $rows = $this->getAllData ();
        $report = array ();
        foreach ( $rows as $row ) {
            $number = $row ['number'];
            $name = $row ['name'];
            $value = $row ['value'];
            $created = $row ['changedOn'];
            if (! in_array ( $name, $cols )) {
                $cols [] = $name;
            }
            // error_log("report=".print_r($report, true));
            if (array_key_exists ( $number, $report )) {
                $val_array = $report [$number];
            } else {
                $val_array = array ();
            }
            $val_array [$name] = $value;
            $report [$number] = $val_array;
            // error_log("val_array=".print_r($val_array, true));
        }
        return array ($cols,$report 
        );
    }
}
?>