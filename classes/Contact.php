<?php
// include the config
require_once (__ROOT__ . '/classes/core/Mysql.php');

class Contact extends Mysql {
    
    
    public function __construct() {
        parent::__construct ();
    }
    
    public function saveContactRequest($name, $contact_phone, $contact_email, $page, $message) {
        $name = $this->quote($name);
        $contact_phone = $this->quote($contact_phone);
        $contact_email = $this->quote($contact_email);
        $page = $this->quote($page);
        $message = $this->quote($message);
        $result = $this->changeRow ( sprintf ( "insert into sms_support values( %s, %s, %s, %s, %s, now());", 
                $name, $contact_phone, $contact_email, $page, $message) );
        error_log("saveContactRequest result=".$result);
    }

}