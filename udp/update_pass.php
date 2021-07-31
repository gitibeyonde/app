<?php include('_header.php'); ?>
<?php
// error_log(__ROOT__);
require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/User.php');
require_once (__ROOT__ . '/classes/NetUtils.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__.'/classes/RegistryPort.php');
// error_reporting(E_ERROR | E_PARSE);

set_time_limit ( 5 );

$user_name = $_SESSION ['user_name'];
$user_email = $_SESSION ['user_email'];
$password = $_SESSION ['password'];
$server = $_GET['server'];
$user = UserFactory::getUser ( $user_name, $user_email );
$pr = new RegistryPort();
$devices = $user->getDevices ();
foreach ( $devices as $device ) {
    list($ip, $port) = $pr->getIpAndPort($device->uuid);

    $client = new NetUtils ( $user_name, $device->uuid, $port );
    $client->register();
    
    $client->sendActionBroker ( $device->uuid, 'Pass', $password );
}
$message = "Action change password is accepted, and is send to your device. It will take sometime for it to be effective ! Please, goto setting and check password update time to confirm all devices are updated";
header ( "Location: https://".$server."/views/not_logged_in.php");
?>
