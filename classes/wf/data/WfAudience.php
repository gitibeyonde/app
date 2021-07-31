<?php
// include the config
require_once(__ROOT__.'/classes/core/Sqlite.php');
require_once(__ROOT__.'/classes/wf/SmsWfUtils.php');

class WfAudience extends Sqlite
{
    private $user_id=null;
    
    //User id is the first paramter
    public function __construct($user_id)
    {
        parent::__construct($user_id, null, self::$AUD);
        $this->user_id = $user_id;
    }
    
    public function deleteWFAudience($table){
        $this->t_delete($table);
    }
    
    public function loadWFAudience($table, $filename){
        //$fkey= $this->user_id . "/" . $name . "/" .basename(basename($_FILES["fileToUpload"]["name"]));
        //$filename = "/Users/aprateek/Desktop/sms_cat_healthcare_speciality.csv";
        error_log("Filenaem=".$filename);
        $pd = array_map('str_getcsv', file($filename));
        $headers=array();
        foreach ($pd[0] as $head){
            $headers[]=trim($head);
        }
        if (count($headers) == 0){
            throw new Exception("The csv file has missing or malformed header.");
        }
        similar_text(trim($headers[0]), "number", $number_sim);
        similar_text(trim($headers[0]), "email", $email_sim);
        $this->log->debug("Header number similar text header=".$headers[0]."similarity number%=".$number_sim."similarity email%=".$email_sim);
        if ($number_sim < 60 && $email_sim < 60){
            throw new Exception("The header is missing the number or email field. The number/email field should be the first column in the csv.");
        }
        error_log("Head=".SmsWfUtils::flatten($headers));
        
        try {
            $create_stmt = "CREATE TABLE IF NOT EXISTS ".$table." (";
            foreach($headers as $head){
                $create_stmt .= self::esc($head)." TEXT ,";
            }
            $create_stmt = substr($create_stmt, 0, strlen($create_stmt) -1);
            $create_stmt .= ");";
            error_log("create stmt=".$create_stmt);
            $this->t_crtinsupd($create_stmt);
        }
        catch(Exception $e){
            throw new Exception("Error encountered while creating schema ".$e->getMessage());
        }
        
        try {
            foreach(array_splice($pd, 1) as $row){
                $insert_stmt="insert into ".$table." values (";
                $data = array();
                for ($i=0; $i<count($pd[0]);$i++){
                    $header = self::esc(trim($pd[0][$i]));
                    $data[$header] = self::esc(trim($row[$i]));
                    $insert_stmt .= "'".self::esc(trim($row[$i]))."',";
                }
                $insert_stmt = substr($insert_stmt, 0, strlen($insert_stmt) -1);
                $insert_stmt .= ");";
                error_log("insert_stmt=".$insert_stmt);
                $this->t_crtinsupd($insert_stmt);
            }
        }
        catch(Exception $e){
            throw new Exception("Error encountered while saving data ".$e->getMessage());
        }
    }
    
    public function getAudience($audience_table){
        return $this->t_data($audience_table);
    }
    
    
}
?>
    