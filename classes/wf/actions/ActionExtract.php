<?php
require_once(__ROOT__ . '/classes/wf/actions/Action.php');
require_once(__ROOT__ . '/classes/wf/checks/CheckFactory.php');

class ActionExtract extends Action {
    
    function __construct($uid, $bid, $number) {
        parent::__construct($uid, $bid, $number);
    }
    
}

?>