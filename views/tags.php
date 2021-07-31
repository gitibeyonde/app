<?php
include ('_header.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/Usage.php');
require_once (__ROOT__ . '/classes/Aws.php');

$user = UserFactory::getUser ( $_SESSION ['user_name'], $_SESSION ['user_email'] );
$devices = $user->getDevices ();
$utils = new Utils();
$aws = new Aws ();
?>

<main>
<div class="container-fluid">
 
    <div class="row">
        <div class="col-sm-12 col-md-3">
         <h5>Tags</h5>
       </div>
    </div>
    <div class="row">
    <?php

    foreach ( $devices as $device ) {
        $tv = $utils->getTags($device->uuid, 5);
            ?>
            <div class="col-sm-6 col-md-3">
                <br/>
                 <?php 
                 if (count($tv)==0){
                     echo "<font color=orange>No tags ".$device->device_name."</font>";
                 }
                 else {
                     echo "<h5><u><font color=green>".$device->device_name."</font></u></h5>";
                     foreach ( $tv as $tag ) {
                        echo '<center><font color=red>'.$tag['name'].'</font></center>';
                        $date = DateTime::createFromFormat('d/m/Y - H:i:s', $tag['time']);
                        echo '<a href=/index.php?view=for_image_header&uuid='.$device->uuid.'&date='.$date->format('Y/m/d').'&time='.$date->format('H').'>';
                        echo '<center><img src="'.$aws->getSignedFileUrl ($tag['url']).'" alt="Loading ..." class="img-responsive" width="180"></center>';
                        echo '</a>';
                     }
                     echo "<center><font color=blue><a href=/index.php?view=".DEVICE_TAGS."&uuid=".$device->uuid."&device_name=".$device->device_name.">".$device->device_name." more..</a></font></center>";
                 }
                ?>
                <hr/>
                <br/>
            </div>
        <?php }?>
    </div>
    <div class="row">
        <br /> <br />
        <br /> <br />
    </div>
 </div>
 </main>
<?php include('_footer.php'); ?>
