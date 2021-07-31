<?php include('_header.php'); 
    require_once(__ROOT__.'/classes/Device.php');
    require_once(__ROOT__.'/config/config.php');
    require_once(__ROOT__.'/classes/Aws.php');
    require_once(__ROOT__.'/classes/Utils.php');
    require_once(__ROOT__.'/classes/Motion.php');
    
    $aws = new Aws();
    $uuid=$_GET['uuid'];
    $dev=new Device();
    $device = $dev->loadDevice($uuid);
    $date=null;
    if (isset($_GET["date"])){
        $date=$_GET["date"];
    }
    else {
        $date = Utils::dateNow($device->timezone);;
    }
    $motions = null;
    if (isset ( $_GET ["time"] )) {
        $motions = $aws->loadTimeMotionDataDesc ( $uuid, $date , $_GET ["time"]);
    }
    else {
        $motions = $aws->loadMotionDataDesc ( $uuid, $date );
    }
    $date_id = str_replace('/', '_', $date);
    $hr_of_day = [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23 ];
?>
   

<div class="container"  style="padding-top: 100px;">

 <div class="row">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
    <font color=orange>Listing--></font>
    <?php foreach ( $hr_of_day as $hour ) { ?>
       <a href="index.php?uuid=<?php echo $device->uuid; ?>&view=<?php echo HISTORY_VIEW; ?>&date=<?php echo $date; ?>&time=<?php echo $hour; ?>"><font color=orange><?php echo $hour; ?></font></a>
    <?php } ?>
    </div>
 </div>
 <div class="row">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
    <font color=yellow>Player--></font>
    <?php foreach ( $hr_of_day as $hour ) { ?>
       <a href="index.php?uuid=<?php echo $device->uuid; ?>&view=<?php echo PLAY_HISTORY; ?>&date=<?php echo $date; ?>&time=<?php echo $hour; ?>"><font color=yellow><?php echo $hour; ?></font></a>
    <?php } ?>
    </div>
 </div>
 
</div>

<?php if (count($motions) == 0) { ?>
<br/>
<br/>
<h3>No alerts found for the date and time selected !</h3>

<?php } ?>

<div class="container">

 <div class="row">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
    
<script type="text/javascript" src="/js/sequencer.bg.js"></script> 

 <script type="text/javascript">
        Sequencer.init({list : [ 

<?php 
    foreach ($motions as $motion){
        $furl = $aws->getSignedFileUrl($motion->image);
            echo '"'.$furl.'", ';
    }
?>
] , folder:"", direction:"-x", playMode:"mouse"})
</script>

</div>
</div>
</div>

<?php include('_footer.php'); ?>
