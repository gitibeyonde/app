<?php
// include the config
require_once (__ROOT__ . '/classes/sms/SmsIntent.php');
require_once (__ROOT__ . '/classes/core/Mysql.php');
require_once (__ROOT__ . '/classes/wf/def/SmsWfNode.php');

class Forum extends Mysql {
 
    public function __construct() {
        parent::__construct ();
    }
    
    public function saveComment($user_name, $title, $comment, $parent_id, $image) {
        $comment = $this->quote($comment);
        $title = $this->quote($title);
        $image = $this->quote($image);
        $result = $this->changeRow ( sprintf ( "insert into sms_forum(user_name, title, comment, parent_id, image, created_at) values( '%s', %s, %s, %d, %s, now());", 
                            $user_name, $title, $comment, $parent_id, $image) );
        error_log("saveComment result=".$result);
    }
    public function getTopLevelComments() {
        return $this->selectRows ('select * from sms_forum where parent_id=-1 order by created_at desc');
    }
    public function getTopic($id) {
        return $this->selectRow ( sprintf ( "select * from sms_forum where id=%d", $id));
    }
    public function getReplies($id) {
        return $this->selectRows ( sprintf ( "select * from sms_forum where parent_id=%d", $id));
    }
    
    public static function guidv4()
    {
        $data = random_bytes(10);
        return substr(bin2hex($data), 0, 10);
    }
}
?>