<?php include('_header.php'); ?>
<?php

require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/Motion.php');

$aws = new Aws ();
$uuid = $_GET ['uuid'];
$dev = new Device ();
$device = $dev->loadDevice ( $uuid );
$date = '';
$today = Utils::dateNow ( $device->timezone );
if (isset ( $_GET ["date"] )) {
    $date = $_GET ["date"];
} else {
    $date = $today;
}
$motions = null;
if (isset ( $_GET ["time"] )) {
    $motions = $aws->loadTimeMotionData ( $uuid, $date , $_GET ["time"]);
}
else {
    $motions = $aws->loadMotionData ( $uuid, $date );
}
$date_id = str_replace ( '/', '_', $date );
$hr_of_day = [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23 ];
?>

<div class="container"  style="padding-top: 100px;">
   <div class="row">
      <div class="col-sm-8 col-md-8 col-md-offset-1">
      <?php if (isset ( $_GET ["time"] )) {?>
         <a href="index.php?uuid=<?php echo $device->uuid; ?>&view=<?php echo PLAY_HISTORY; ?>&date=<?php echo $date; ?>&time=<?php echo $_GET ["time"]; ?>"> 
         <?php } else {?>
         <a href="index.php?uuid=<?php echo $device->uuid; ?>&view=<?php echo PLAY_HISTORY; ?>&date=<?php echo $date; ?>"> 
         <?php } ?>
         <button type="button" class="btn btn-info btn-lg btn-block" style="position: center;">Player</button></a>
       </div>
    </div>
 <div class="row">
    <div class="col-sm-8 col-md-8 col-md-offset-2">
    <?php foreach ( $hr_of_day as $hour ) { ?>
       <a href="index.php?uuid=<?php echo $device->uuid; ?>&view=<?php echo HISTORY_VIEW; ?>&date=<?php echo $date; ?>&time=<?php echo $hour; ?>"><?php echo $hour; ?></a>
    <?php } ?>
    </div>
 </div>
</div>

<?php
if (count ( $motions ) > 0) {
    ?>
<div class="container" id="dayone1">
    <div class="row" id="<?php echo $date_id;?>">
   <?php
    $count = 0;
    foreach ( $motions as $motion ) {
        $furl = $aws->getSignedFileUrl ( $motion->image );
        $count = $count +1;
        $ismp4 = strpos($furl, '.mp4') !== false;
        if ($ismp4 && $count > 10) break;
        ?>
       
       
           <div class="container">
               <div class="col-sm-8 col-md-8 col-md-offset-2">
                         <?php if ($ismp4) { ?>
                                   <video 
                                      width="100%"
                                      style="display: block;"
                                      controls
                                    >
                                        <source  src="<?php echo $furl; ?>"  type="video/mp4"  autoload="false" />
                                   </video>
                            <?php } else { ?>
                                  <form class="form-horizontal" name=tagPicture<?php echo $count; ?> method=GET action="views/history_tag.php"  target="_blank" >
                                      <button class="btn btn-default" style="border: none; background: none; padding: 0; outline: none;">
                                        <img id="#himage" src="<?php echo $furl; ?>" alt="Loading..." class="img-responsive" width="640" height="480"/>
                                      </button> 
                                      <input type=hidden name=time value="<?php echo $motion->datetime ?>" /> 
                                      <input type=hidden name=date value="<?php echo $date ?>" /> 
                                      <input type=hidden name=uuid value="<?php echo $uuid ?>" /> 
                                      <input type=hidden name=furl value="<?php echo $motion->image ?>" /> 
                                </form>
                             <?php } ?>
               </div>
           </div>
    <?php } ?>
        </div>
</div>
<div class="container" id="Loading">
    <div class="row">
        <div class="col-sm-8 col-md-8 col-md-offset-2">
            <img src="img/spinner.gif" width="30px" height="30px">
        </div>
    </div>
</div>
<?php } else { ?>
<br />
<br />
<h3>No recorded alerts on this date !</h3>
<?php }?>
<script>
  var pickerdate="<?php echo $date; ?>";
    var phpuuid="<?php echo $uuid; ?>";
    
</script>
<script src="js/forimageheaderscript.js"></script>
