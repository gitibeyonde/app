
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

if (isset ( $_GET ["action"] )) {
    $action = $_GET ["action"];
    if ($action == "delete"){
        $dev->deleteHistory($uuid);
        $A = new Aws();
        $A->deleteMotionData($uuid);
    }
}


$device = $dev->loadDevice ( $uuid );
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
<div class="container-fluid top">

<div class="row align-items-center">
    <div class="col-md-2 col-lg-3 col-xl-4">
       <a class="nav-link" href="/index.php?view=<?php echo HISTORY_VIEW ?>&uuid=<?php echo $device->uuid; ?>&action=delete"><h1><span class="material-icons md-48 red">delete_forever</span></h1></a>
    </div>
    <div class="col-12 col-sm-12 col-md-8 col-lg-6 col-xl-6">
    <form action="index.php" method=GET>
      <label>&nbsp;Camera:</label>
       <input type="text" name="uuid" value="<?php echo $device->uuid; ?>" readonly><?php echo $dev->device_name; ?>
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

   <?php
        $count = 0;
        foreach ( $motions as $motion ) {
                $count = $count +1;
                $furl = $aws->getSignedFileUrl ( $motion->image );
                $ismp4 = strpos($furl, '.mp4') !== false;
           ?>
<div class="row align-items-center">
    <div class="col-md-2 col-lg-3 col-xl-4">
    </div>
    <div class="col-12 col-sm-12 col-md-8 col-lg-6 col-xl-6">
                     <?php if ($ismp4) { ?>
                               <video
                                  width="100%"
                                  style="display: block;"
                                  controls
                                >
                                    <source  src="<?php echo $furl; ?>"  type="video/mp4"/>
                               </video>
                        <?php } else { ?>
                              <form class="form-horizontal" name=tagPicture<?php echo $count; ?> method=GET action="index.php?view=<?php echo HISTORY_TAG; ?>" target="_blank" >
                                  <button class="btn btn-default" style="border: none; background: none; padding: 0; outline: none;">
                                    <img id="#himage" src="<?php echo $furl; ?>" alt="Loading..." class="img-fluid" width="100%"/>
                                    <?php echo $motion->datetime; ?>
                                  </button>
                                  <input type=hidden name=time value="<?php echo $motion->datetime; ?>" />
                                  <input type=hidden name=date value="<?php echo $date; ?>" />
                                  <input type=hidden name=uuid value="<?php echo $uuid; ?>" />
                                  <input type=hidden name=furl value="<?php echo $motion->image; ?>" />
                            </form>
                         <?php } ?>
           </div>
  </div>
        <?php } ?>
<?php } else { ?>
<br/>
<br/>
<h3>No alerts found for the date and time selected !</h3>
</div>
<?php }
include ('_footer.php'); ?>
