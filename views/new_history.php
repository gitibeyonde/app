
<?php include('_header.php'); ?>
<?php

require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/Motion.php');
if (!isset($_SESSION['user_id'])){
    echo "Unauthorized Access";
    die;
}
$aws = new Aws ();
$uuid = $_GET ['uuid'];
$dev = new Device ();
$device = $dev->loadDevice ( $uuid );
$devices = $dev->loadUserDevices($_SESSION ['user_name']);
$date = null;
if (isset ( $_GET ["date"] )) {
    $date = $_GET ["date"];
} else {
    $date = Utils::dateNow ( $device->timezone );
}
$time=null;
if (isset ( $_GET ["time"] ) && $_GET ["time"] > 0) {
    $time = $_GET ["time"];
}
$motions = null;
if ($time != null) {
    $motions = $aws->loadTimeMotionData ( $uuid, $date , $time);
}
else {
    $motions = $aws->loadMotionData ( $uuid, $date );
}
$date_id = str_replace('/', '_', $date);
$last_90_days = Utils::getLastNDays(90, 'Y/m/d');
$hr_of_day = [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23 ];

error_log("Date= $date Time=$time");
?>
<div class="container"  style="padding-top: 100px;">

<div class="row">
<div class="col-md-3 col-lg-4 col-xl-4">
<br/>
<br/>
<br/>
</div>
</div>
<div class="row align-items-center">
    <div class="col-md-3 col-lg-4 col-xl-4">
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
    <form action="index.php" method=GET>
      <label>&nbsp;Camera:</label><select name="uuid">
        <?php foreach ( $devices as $dev ) { ?>\
         <option value="<?php echo $dev->uuid; ?>" <?php echo ($dev == $device ? "selected=selected" : ""); ?>"><?php echo $dev->device_name; ?></option>
        <?php } ?>
      </select>
      <label>&nbsp;Date:</label><select name="date">
        <?php foreach ( $last_90_days as $day ) { ?>\
       	 <option value="<?php echo $day; ?>" <?php echo ($day == $date ? "selected=selected" : ""); ?>"><?php echo $day; ?></option>
        <?php } ?>
      </select>
      <label> &nbsp;Hour:</label><select name="time">
        <option value="" selected="selected"></option>
        <?php foreach ( $hr_of_day as $hour ) { ?>
       	 <option value="<?php echo $hour; ?>" ><?php echo $hour; ?></option>
        <?php } ?>
      </select>
      <label>&nbsp;</label>
      <input type="submit" value="Go">
      <input type="hidden" name="view" value="<?php echo HISTORY_VIEW; ?>">
    </form>
    </div>
</div>


<?php
if (count($motions) > 0) {
?>
 <div class="row align-items-center">
   <div class="col-md-3 col-lg-4 col-xl-4">
   </div>
   <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
   <?php
        $ismp4 = false;
        $count = 0;
        foreach ( $motions as $motion ) {
            $furl = $aws->getSignedFileUrl ( $motion->image );
            $ismp4 = strpos($furl, '.mp4') !== false;
            if ($count > 1) break;
        }
        
        if (ismp4) { 
            $count = 0;
            $mp4list = array();
            foreach ( $motions as $motion ) {
                    $count = $count +1;
                    $furl = $aws->getSignedFileUrl ( $motion->image ); 
                    $mp4list[$count]['Image'] = $furl;
                    $mp4list[$count]['Time'] = $motion->time;
            }
                    ?>
                   <video 
                      width="100%"
                      style="display: block;"
                      controls
                    >
                        <source  src="<?php echo $furl; ?>"  type="video/mp4" autoload="false"/>
                   </video>
       <?php 
            }  else {
            $count = 0;
            foreach ( $motions as $motion ) {
                    $count = $count +1;
                    $furl = $aws->getSignedFileUrl ( $motion->image );      ?>
       
                        <form class="form-horizontal" name=tagPicture<?php echo $count; ?> method=GET action="views/history_tag.php" target="_blank" >
                              <button class="btn btn-default" style="border: none; background: none; padding: 0; outline: none;">
                                <img id="#himage" src="<?php echo $furl; ?>" alt="Loading..." class="img-responsive" width="100%"/>
                              </button> 
                              <input type=hidden name=time value="<?php echo $motion->datetime ?>" /> 
                              <input type=hidden name=date value="<?php echo $date ?>" /> 
                              <input type=hidden name=uuid value="<?php echo $uuid ?>" /> 
                              <input type=hidden name=furl value="<?php echo $motion->image ?>" /> 
                        </form>
        <?php } ?>
<?php }  ?>

        </div>
   </div>
<?php } else { ?>
<br/>
<br/>
<h3>No alerts found for the date and time selected !</h3>
</div>
<?php }
include ('_footer.php'); ?>