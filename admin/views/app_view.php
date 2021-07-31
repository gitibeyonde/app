<?php include('_header.php'); ?>
<?php
error_log(__ROOT__);
require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');

$devices = Device::loadAllDevices();
$client = new Aws ();
?>

    <div class="row">
        <?php
        foreach ( $devices as $device ) {
            $today = Utils::dateNow ( $device->timezone );
            ?>
            <div class="col-sm-4 col-md-2">
            <iframe class="embed-responsive-item" scrolling="no" frameborder="0" width="432" height="324" id="<?php echo $device->uuid; ?>0" style="display: block"
                src="../views/motion.php?&timezone=<?php echo $device->timezone; ?>&uuid=<?php echo $device->uuid; ?>&animate=true"> </iframe>
              <h5><?php echo $device->uuid; ?>&nbsp;<=>&nbsp;<?php echo $device->device_name; ?></h5>
             </div>       
                <?php
        }
        ?>
       </div>
 
     
<?php include('_footer.php'); ?>
