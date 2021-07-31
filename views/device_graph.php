<?php include('_header.php'); ?>
<?php

require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/User.php');
require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/DeviceCert.php');
require_once (__ROOT__.'/classes/RegistryPort.php');
require_once(__ROOT__.'/classes/DeviceContext.php');

// error_reporting(E_ERROR | E_PARSE);

$user_id = $_SESSION ['user_id'];
$user_name = $_SESSION ['user_name'];
$uuid = $_GET ['uuid'];
$device_name = $_GET ['device_name'];
$user_email = $_SESSION ['user_email'];
$thisbox = 'default';
if (isset ( $_GET ['box'] )) {
    $thisbox = $_GET ['box'];
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
$boxes = $user->getBoxes ();
$pr = new RegistryPort();

set_time_limit ( 5 );
$client = new Aws ();
$timezone = $device->timezone ;
$today = Utils::dateNow ( $timezone);
list ( $furl, $datetime ) = $client->latestMotionDataUrl ( $device->uuid, $today );
list($ip, $port) = $pr->getIpAndPort($device->uuid);
?>

<div class="container"  style="padding-top: 100px;">
    
    <h3>Various graphs for data collected from the device</h3><hr/>
    
    <br/>
    
     <div class="row">
        <div class="col-sm-12 col-md-6">
            <a href="index.php?uuid=<?php echo $uuid; ?>&view=<?php echo TEMP_VIEW; ?>&timezone=<?php echo $timezone; ?>"> <b>Temperature &nbsp; &nbsp;<span class="glyphicon glyphicon-scale"></span></b>
            </a>
            <div class="embed-responsive  embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                    src="/views/graph/temp.php?uuid=<?php echo $uuid; ?>&timezone=<?php echo $timezone; ?>"></iframe>
            </div>
        </div>
   </div>
    
     <div class="row">
        <div class="col-sm-12 col-md-6">
            <a href="index.php?uuid=<?php echo $uuid; ?>&view=<?php echo MOTION_VIEW; ?>&timezone=<?php echo $timezone; ?>"> <b><span class="glyphicon glyphicon-cog">Activity &nbsp; &nbsp;</span></b>
            </a>
            <div class="embed-responsive  embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                    src="/views/graph/motion.php?uuid=<?php echo $uuid; ?>&timezone=<?php echo $timezone; ?>"></iframe>
            </div>
        </div>
   </div>
     <div class="row">
        <div class="col-sm-12 col-md-6">
            <a href="index.php?uuid=<?php echo $uuid; ?>&view=<?php echo IMAGE_PARAMS_VIEW; ?>&timezone=<?php echo $timezone; ?>"> <b>Camera Params &nbsp; &nbsp;<span class="glyphicon glyphicon-scale"></span></b>
            </a>
            <div class="embed-responsive  embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                    src="/views/graph/image.php?uuid=<?php echo $uuid; ?>&timezone=<?php echo $timezone; ?>"></iframe>
            </div>
        </div>
   </div>
    
     
     
    <div class="row">
        <br /> <br />
              <a href="javascript:window.close();">
                <button type="button" class="btn btn-info btn-lg btn-block" style="position: center;">Close</button>
            </a>
    </div>
    
    
    <div class="row">
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
    </div>
 
</div>
<?php include('_footer.php'); ?>
