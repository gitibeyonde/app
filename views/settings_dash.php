<?php
include ('_header.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/Usage.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/RegistryPort.php');
require_once (__ROOT__ . '/common/settings_include.php');
require_once (__ROOT__ . '/classes/AlertConfig.php');

set_time_limit ( 5 );

// error_reporting(E_ERROR | E_PARSE);
if (isset($_GET['user_id'])){
    $user_id = $_GET ['user_id'];
}
else {
    $user_id = $_SESSION ['user_id'];
}
if (isset($_GET['user_name'])){
    $user_name = $_GET ['user_name'];
}
else {
    $user_name = $_SESSION ['user_name'];
}
$uuid = $_GET ['uuid'];
$device_name = $_GET ['device_name'];
$user_email = $_SESSION ['user_email'];
$role = $_SESSION ['role'];
$box = $_GET ['box'];
$timezone = $_GET ['timezone'];
$local = $_GET['local'];
$loc = $_GET['loc'];
$token = $_GET['tk'];
if (isset($_GET['tab'])){
    $tab = $_GET['tab'];
}
else {
    $tab = 'device';
}

$utils = new Utils ();
$check_uuid = $utils->validateDevice ( $user_name, $uuid );
if ($check_uuid != $uuid) {
    echo "Bad access";
    include ('_footer.php');
    return;
}

$user = UserFactory::getUser ( $user_name, $user_email );
$device = $user->getDevice ( $uuid );
$devices = $user->getDevices ( $uuid );
//error_log("Device Settings = ".print_r($device->setting, true));

$pr = new RegistryPort();
list($ip, $port) = $pr->getIpAndPort($device->uuid);
$remoteip = urldecode($_SERVER['REMOTE_ADDR']);
$settings = json_decode(trim(preg_replace('/\s+/', ' ', $device->setting)), true);

//error_log($uuid."   Settings=".print_r($settings, true));

$aws = new Aws ();
$alert_config = new AlertConfig();

$today = Utils::dateNow ( $timezone );
list ( $furl, $datetime ) = $aws->latestMotionDataUrl ( $uuid, $today );

$dev_alert_config = $alert_config->loadDeviceAlertConfig($uuid);
$categories = $alert_config->loadManualCategories($uuid);
if ($dev_alert_config == null){
    $dev_alert_config = $alert_config;
}
$cbits = str_split($dev_alert_config->grid, 1);
$mbits = str_split($dev_alert_config->email_mask, 1);
$pbits = str_split($dev_alert_config->pns_mask, 1);
$share_name= $utils->getShareName($uuid);
$boxes = $user->getBoxes ();
$phone=$_SESSION['user_phone'];


$cap="";

 if (strpos($device->capabilities, "CAMERA") !== false) { 
     $cap .= " CAMERA";
 }
 if (strpos($device->capabilities, "AUDIO") !== false) {
     $cap .= " AUDIO";
 } 
 if (strpos($device->capabilities, "TEMPERATURE") !== false) {
     $cap .= " TEMPERATURE";
 }
 if (strpos($device->capabilities, "MOTION") !== false) {
     $cap .= " MOTION";
 } 
 if (strpos($device->capabilities, "BELL") !== false) {
     $cap .= " BELL";
 }  
 if (strpos($device->capabilities, "MIC") !== false) {
     $cap .= " MIC";
 } 
 if (strpos($device->capabilities, "SPEAKER") !== false) {
     $cap .= " SPEAKER";
 } 
 if (strpos($device->capabilities, "SIM") !== false) {
     $cap .= " SIM";
     $tab="gprs";
 } 
?>
<main>
<div class="container">
<div class="row">
	<div class="col-md-2 col-sm-1 col-sx-0 col-lg-3">
	</div>
	<div class="col-md-8 col-sm-10 col-sx-12 col-lg-6">
           		<h3> <?php echo substr($device->device_name, 0 , 12) ?> - [<?php echo $device->profile; ?> ] - <?php echo substr($device->uuid, 0 , 12) ?></h3>
           		<h4><?php echo $cap;?></h4> 
            <hr/>
    </div>
    <div class="col-md-2 col-sm-1 col-sx-0 col-lg-3">
    </div>
 </div>
    <div class="row">
        <div class="nav nav-pills mb-3" id="v-pills-tab" role="tablist" >
             <?php  if (strpos($cap, 'SIM') !== false) { ?>
                <a class="nav-link <?php echo ($tab == "gprs" ? "active": ""); ?>" id="v-pills-gprs-tab" data-toggle="pill" href="#v-pills-gprs" 
                                role="tab" aria-controls="v-pills-gprs" aria-selected="<?php echo ($tab == "gprs" ? "true": "false"); ?>">GPRS Functions</a>
             <?php } ?>
            <?php  if (strpos($cap, 'MOTION') !== false) { ?>
            	<a class="nav-link <?php echo ($tab == "device" ? "active": ""); ?>" id="v-pills-device-tab" data-toggle="pill" href="#v-pills-device" 
                            role="tab" aria-controls="v-pills-device" aria-selected="<?php echo ($tab == "device" ? "true": "false"); ?>">Device Setup</a>
                <a class="nav-link <?php echo ($tab == "motion" ? "active": ""); ?>" id="v-pills-motion-tab" data-toggle="pill" href="#v-pills-motion" 
                                role="tab" aria-controls="v-pills-motion" aria-selected="<?php echo ($tab == "motion" ? "true": "false"); ?>">Motion Setup</a>
                <a class="nav-link <?php echo ($tab == "alert" ? "active": ""); ?>" id="v-pills-alert-tab" data-toggle="pill" href="#v-pills-alert" 
                                role="tab" aria-controls="v-pills-alert" aria-selected="<?php echo ($tab == "alert" ? "true": "false"); ?>">Alert Config</a>
                <a class="nav-link <?php echo ($tab == "boxing" ? "active": ""); ?>" id="v-pills-boxing-tab" data-toggle="pill" href="#v-pills-boxing" 
                                role="tab" aria-controls="v-pills-boxing" aria-selected="<?php echo ($tab == "boxing" ? "true": "false"); ?>">Boxing</a>
                <a class="nav-link <?php echo ($tab == "sharing" ? "active": ""); ?>" id="v-pills-sharing-tab" data-toggle="pill" href="#v-pills-sharing" 
                                role="tab" aria-controls="v-pills-sharing" aria-selected="<?php echo ($tab == "sharing" ? "true": "false"); ?>">Sharing</a>
            <?php } ?>
        </div>
    </div>
    <div class="tab-content" id="v-pills-tabContent">
     <?php  if (strpos($cap, 'SIM') !== false) { ?>
        <div class="tab-pane fade <?php echo ($tab == "gprs" ? "show active": ""); ?>" id="v-pills-gprs" role="tabpanel" aria-labelledby="v-pills-gprs-tab">
            <?php include('common/sms_function.php'); ?>
        </div>
	 <?php } ?>
     <?php  if (strpos($cap, 'MOTION') !== false) { ?>
        <div class="tab-pane fade <?php echo ($tab == "device" ? "show active": ""); ?>" id="v-pills-device" role="tabpanel" aria-labelledby="v-pills-device-tab">
            <?php include('common/device_settings.php'); ?>
        </div>
        <div class="tab-pane fade <?php echo ($tab == "motion" ? "show active": ""); ?>" id="v-pills-motion" role="tabpanel" aria-labelledby="v-pills-motion-tab">
               <?php include('common/motion_settings.php'); ?>
        </div>
        <div class="tab-pane fade <?php echo ($tab == "alert" ? "show active": ""); ?>" id="v-pills-alert" role="tabpanel" aria-labelledby="v-pills-alert-tab">
               <?php include('common/alert_settings.php'); ?>
        </div>
        <div class="tab-pane fade <?php echo ($tab == "boxing" ? "show active": ""); ?>" id="v-pills-boxing" role="tabpanel" aria-labelledby="v-pills-boxing-tab">
               <?php include('common/box_settings.php'); ?>
        </div>
        <div class="tab-pane fade <?php echo ($tab == "sharing" ? "show active": ""); ?>" id="v-pills-sharing" role="tabpanel" aria-labelledby="v-pills-sharing-tab">
                <?php include('common/share_settings.php'); ?>
        </div>
    <?php } ?>
</div> 

    <?php include('common/add_space.php'); ?>
    <?php include('common/add_space.php'); ?>
    <?php include('common/add_space.php'); ?>
       
    </div> 
</main>
  
<?php include('_footer.php'); ?>
