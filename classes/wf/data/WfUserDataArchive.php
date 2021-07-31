<?php
// include the config
require_once (__ROOT__ . '/classes/core/Sqlite.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');

class WfUserDataArchive extends Sqlite {
    private $user_id = null;
    
    // User id is the first param
    public function __construct($uid, $bid) {
        parent::__construct ( $uid, $bid, self::$UD );
        $this->user_id = $uid;
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
        $this->t_crtinsupd ( sprintf ( "insert or replace into user_data_archive(number, name, value, changedOn) values( '%s', '%s', '%s', datetime('now'));", $number, $name, $value ) );
    }
    public function getUserData($number, $name) {
        return $this->single_value ( sprintf ( "select value from user_data_archive where number='%s' and name='%s'", $number, $name ) );
    }
    public function getAllUserData($number) {
        return $this->multiple_rows_cols ( sprintf ( "select rowid, name, value, changedOn from user_data_archive where number='%s'", $number ) );
    }
    public function getAllData() {
        return $this->multiple_rows_cols ( sprintf ( "select * from user_data_archive order by number;" ) );
    }
    public function deleteUserData($number) {
        $this->t_crtinsupd ( sprintf ( "delete from user_data_archive where number='%s'", $number ) );
    }
    public function deleteUserNameValue($number, $name) {
        $this->t_crtinsupd ( sprintf ( "delete from user_data_archive where number='%s' and name='%s'", $number, $name ) );
    }
    public function generateReport() {
        $cols = array ();
        $rows = $this->getAllData ();
        $report = array ();
        foreach ( $rows as $row ) {
            $number = $row ['number'];
            $name = $row ['name'];
            if ($name == "annotate")
                continue;
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