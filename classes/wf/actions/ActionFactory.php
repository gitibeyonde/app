<?php

class ActionFactory {
    
    private static $_list = array(  
            "choices" => "ActionChoice",
            "intent" => "ActionIntent",
            "search" => "ActionSearch",
            "unmatched" => "ActionUnmatched",
            "extract" => "ActionExtract"
    );
    
    public static function _get($uid, $bid, $number, $name){
        $_log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];;
        $_log->trace("Name=".$name);
        foreach(ActionFactory::$_list as $key=>$value){
            if ($key == $name){
                include_once __ROOT__ . '/classes/wf/actions/'.$value.".php";
                return new $value($uid, $bid, $number);
            }
        }
    }
    
}

?>