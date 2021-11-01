<?php

define ( '__ROOT__',  dirname ( __FILE__ ));

// include the config
require_once('config/config.php');

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once('translations/en.php');

// load the login class
require_once('classes/Login.php');

error_log(print_r($_POST, true));
// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

// the user has just successfully entered a new password
// so we show the index page = the login page
if ($login->passwordResetWasSuccessful() == true && $login->passwordResetLinkIsValid() != true) {

    include("login.php");

} else {
    // show the request-a-password-reset or type-your-new-password form
    include("views/password_reset.php");
}
