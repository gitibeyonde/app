<?php 
require_once (__ROOT__ . '/classes/core/Mysql.php');
require_once (__ROOT__ . '/classes/core/Log.php');

class ChatDb extends Mysql 
{
    public function __construct()
    {
        parent::__construct();
    }
    public function saveText($tags, $text) {
        $tags = $this->quote($tags);
        $text = $this->quote($text);
        $this->changeRow ( sprintf ( "insert into sms_chat_db values(  %s, %s);", $tags, $text) );
    }
    public function getTexts() {
        return $this->selectRows ('select * from sms_chat_db');
    }
    
    public function getText($id) {
        return $this->selectRows ( sprintf ( 'select * from sms_chat_db where id=%d;', $id ) );
    }
    public function deleteText($id) {
        $this->changeRow ( sprintf ( 'delete from sms_chat_db where id=%d;', $id ) );
    }
}


?>