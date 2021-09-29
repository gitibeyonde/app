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

$cap = null;
if (! isset($_SESSION['capability'])) {
    foreach ( $devices as $device ) {
        error_log("Cap=".$device->capabilities);
        if (strpos($device->capabilities, "CAMERA") !== false) {
            $cap = $cap." CAMERA";
        }
        if (strpos($device->capabilities, "MIC") !== false) {
            $cap = $cap." MIC";
        }
        if (strpos($device->capabilities, "SPEAKER") !== false) {
            $cap = $cap." SPEAKER";
        }
        if (strpos($device->capabilities, "MOTION") !== false) {
            $cap = $cap." MOTION";
        }
        if (strpos($device->capabilities, "MOTION") !== false) {
            $cap = $cap." MOTION";
        }
        if (strpos($device->capabilities, "TEMPERATURE") !== false) {
            $cap = $cap." TEMPERATURE";
        }
        if (strpos($device->capabilities, "SIM") !== false) {
            $cap = $cap." SIM";
        }
    }
    $_SESSION['capability'] = $cap;
}
else {
    $cap = $_SESSION['capability'];
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
<?php  if (strpos($cap, 'SIM') !== false) { ?>
<div class="row">
        <a class="col-2 btn btn-primary" data-bs-target="#v-pills-gprs-tab" data-bs-toggle="collapse"  aria-expanded="false"
        		  type="button" aria-controls="v-pills-gprs">GPRS Functions</a>
      <div class="col-10">
        <div class="collapse multi-collapse" id="v-pills-gprs-tab">
          <div class="card card-body">
             <?php include('common/sms_function.php'); ?>
          </div>
        </div>
      </div>
</div>
<?php } ?>
<?php  if (strpos($cap, 'MOTION') !== false) { ?>
<div class="row">
<a class="col btn btn-primary" data-bs-target="#v-pills-sharing" data-bs-toggle="collapse"  aria-expanded="true"
            type="button" aria-controls="v-pills-sharing">Sharing</a>
      <div class="col-10">
        <div class="collapse show multi-collapse" id="v-pills-sharing">
          <div class="card card-body">
            <?php include('common/share_settings.php'); ?>
          </div>
        </div>
      </div>
</div>
<div class="row">
    	<a class="col-2 btn btn-primary" data-bs-target="#v-pills-device" data-bs-toggle="collapse"  aria-expanded="false"
                    type="button" aria-controls="v-pills-device">Device Setup</a>
      <div class="col-10">
        <div class="collapse multi-collapse" id="v-pills-device">
          <div class="card card-body">
            <?php include('common/device_settings.php'); ?>
          </div>
        </div>
      </div>
</div>
<div class="row">

        <a class="col-2 btn btn-primary" data-bs-target="#v-pills-motion" data-bs-toggle="collapse"  aria-expanded="false"
                    type="button" aria-controls="v-pills-motion">Motion Setup</a>
      <div class="col-10">
        <div class="collapse multi-collapse" id="v-pills-motion">
          <div class="card card-body">
             <?php include('common/motion_settings.php'); ?>
          </div>
        </div>
      </div>
</div>
<div class="row">
        <a class="col-2 btn btn-primary" data-bs-target="#v-pills-alert" data-bs-toggle="collapse"  aria-expanded="false"
                    type="button" aria-controls="v-pills-alert">Alert Config</a>
      <div class="col-10">
        <div class="collapse multi-collapse" id="v-pills-alert">
          <div class="card card-body">
             <?php include('common/alert_settings.php'); ?>
          </div>
        </div>
      </div>
</div>
<div class="row">
        <a class="col-2 btn btn-primary" data-bs-target="#v-pills-boxing" data-bs-toggle="collapse"  aria-expanded="false"
                    type="button" aria-controls="v-pills-boxing">Boxing</a>
      <div class="col-10">
        <div class="collapse multi-collapse" id="v-pills-boxing">
          <div class="card card-body">
            <?php include('common/box_settings.php'); ?>
          </div>
        </div>
      </div>
</div>
<?php } ?>



    <?php include('common/add_space.php'); ?>
    <?php include('common/add_space.php'); ?>
    <?php include('common/add_space.php'); ?>

    </div>
</main>

<?php include('_footer.php'); ?>
