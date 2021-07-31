<?php

define ( '__ROOT__',  dirname ( __FILE__ ));

// check for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once(__ROOT__.'/libraries/password_compatibility_library.php');
}
// include the config
require_once(__ROOT__.'/config/config.php');

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once(__ROOT__.'/translations/en.php');

// load the registration class
require_once(__ROOT__.'/classes/Registration.php');
require_once(__ROOT__.'/classes/Login.php');

// create the registration object. when this object is created, it will do all registration stuff automatically
// so this single line handles the entire registration process.
$registration = new Registration();



if ($registration->registration_successful) {
    // showing the register view (with the registration form, and messages/errors)
    include(__ROOT__.'/login.php');
}
else if ($registration->verification_successful) {
    // showing the register view (with the registration form, and messages/errors)
    include(__ROOT__.'/login.php');
    $all_messages="";
    if ($registration->errors) {
        foreach ( $registration->errors as $error ) {
            $all_messages = $all_messages.$error;
        }
    }
    if ($registration->messages) {
        foreach ( $registration->messages as $message ) {
            $all_messages = $all_messages.$message;
        }
    }
}
else {
    include(__ROOT__.'/views/register.php');
}
