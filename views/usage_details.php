<?php include('_header.php'); ?>
<?php

require_once(__ROOT__.'/classes/UserFactory.php');
require_once(__ROOT__.'/classes/User.php');
require_once(__ROOT__ .'/classes/Device.php');
require_once(__ROOT__ .'/classes/Aws.php');
require_once(__ROOT__.'/classes/Utils.php');
    
error_reporting(E_ERROR | E_PARSE);

$user_id=$_SESSION['user_id'];
$user_name=$_SESSION['user_name'];
$uuid = $_GET ['uuid'];
$device_name = $_GET ['device_name'];
$user_email = $_SESSION['user_email'];

$utils = new Utils();
$check_uuid = $utils->validateDevice($user_name, $uuid);
if ($check_uuid != $uuid){
    echo "Bad access";
    include('_footer.php');
    return;
}

$user = UserFactory::getUser($user_name, $user_email);
$device = $user->getDevice($uuid);

set_time_limit(5);
$client = new Aws ();
$today=Utils::dateNow($device->timezone);
list($furl, $datetime)= $client->latestMotionDataUrl($device->uuid, $today);

?>

<div class="container">


    <div class="row">
        <br/>
        <br/>
        <br/>
        <br/>
    </div>

</div>


<?php include('_footer.php'); ?>
