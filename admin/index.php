<?php

define ( '__ROOT__', dirname ( dirname ( __FILE__ )));

// include the config
require_once(__ROOT__.'/config/config.php');

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once(__ROOT__.'/translations/en.php');

// check for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once(__ROOT__.'/libraries/password_compatibility_library.php');
}

// load the login class
require_once(__ROOT__.'/classes/Login.php');


// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

//error_log("Login = ".print_r($login, true));
//error_log("Role = ".$login->getUserRole());

if ($login->getUserRole() == "ADMIN"){

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['view'])){
    	$login->setView($_POST['view']);
    }
    else if (isset($_GET['view'])) {
    	unset($_GET['logout']);
    	$login->setView($_GET['view']);
    }

    error_log("View = ".$login->getView());

    if  ($login->getView() == MAIN_VIEW){
        if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
            include("views/main_view.php");
        }
        else {
            include("views/not_logged_in.php");
        }
    }
    else if  ($login->getView() == ADMIN_MAIN_VIEW){
        if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        	include("views/main_view.php");
        }
        else {
        	include("views/not_logged_in.php");
        }
    }
    elseif  ($login->getView() == ADMIN_USAGE){
        if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        	include("views/usage.php");
        }
        else {
        	include("views/not_logged_in.php");
        }
    }
    else {
        include("views/not_logged_in.php");
    }
}
else {
    $login->messages[0] = "Unauthorized access";
    include("views/not_logged_in.php");
}

?>
