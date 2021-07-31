<?php

class CheckFactory {
    
    private static $_list = array(  "name" => "CheckNameAge",
                                    "age" => "CheckNameAge",
                                    "appointment" => "CheckAppointment"
                                );
   
    
    public static function _get($bid, $number, $name){
        $_log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];;
        $_log->trace("Name=".$name);
        foreach(CheckFactory::$_list as $key=>$value){
            if ($key == $name){
                include_once __ROOT__ . '/classes/wf/checks/'.$value.".php";
                return new $value($bid, $number);
            }
        }
    }
    
    public static function _check($bid, $number, $value, $name){
        $_log = isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];;
        $_log->trace("name=".$name);
        $check = CheckFactory::_get($bid, $number, $name);
        if ($check != null){
            $_log->trace("check class=".get_class($check));
            return $check->range($value, $name);
        }
        return "";
    }
    
}

?>