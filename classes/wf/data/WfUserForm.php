<?php
// include the config
require_once (__ROOT__ . '/classes/core/Sqlite.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
class WfUserForm extends Sqlite {
    private $user_id = null;

    // User id is the first param
    public function __construct($uid) {
        parent::__construct ( $uid, "forms", self::$UD );
        $this->user_id = $uid;
        if (! $this->t_exists ( "form_metadata" )) {
            $this->t_crtinsupd ( "CREATE TABLE IF NOT EXISTS form_metadata ( name text, metadata text, changedOn text, unique(name)) ;" );
        }
    }
    public function createFormTable($table, $col, $input){
        $new_cols = array();
        $type= array();
        for($i=0; $i<count($col); $i++){
            array_push($new_cols, $this->esc($col[$i]."->".$input[$i]));
            array_push($type, "TEXT");
        }
        $this->createTable($table, $new_cols, $type);
    }
    public function deleteForm($table){
        $this->t_delete($table);
    }

    public function saveFormType($table, $type){
        $this->query(sprintf ("replace into form_metadata values ('%s', '%s', '%s');", $table, $type, strtotime ( 'now' )));
    }
    public function getFormType($table){
        return $this->single_value(sprintf ("select metadata from form_metadata where name='%s';", $table));
    }
}
?>