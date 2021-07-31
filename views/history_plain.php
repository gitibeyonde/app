
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
?>

                   
<?php
if (count($motions) > 0) {
?>
    <div class="row anything">
   <?php
    $count = 0;
    foreach ( $motions as $motion ) {
        $furl = $aws->getSignedFileUrl ( $motion->image );
        $count = $count +1;
        $ismp4 = strpos($furl, '.mp4') !== false;
       ?> 
       
       
           <div class="container">
               <div class="col-sm-8 col-md-8 col-md-offset-2">
                         <?php if ($ismp4) { ?>
                                   <video 
                                      width="100%"
                                      style="display: block;"
                                      controls
                                    >
                                        <source  src="<?php echo $furl; ?>"  type="video/mp4" />
                                   </video>
                            <?php } else { ?>
                                  <form class="form-horizontal" name=tagPicture<?php echo $count; ?> method=GET action="views/history_tag.php">
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

<?php } else { ?>
<br/>
<br/>
<h3>No recorded alerts on this date !</h3>

<?php }?>
