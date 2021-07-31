
<style>
    .table td, .table th {
        padding: .75rem;
        vertical-align: middle !important;
        border-top: 1px solid #dee2e6;
    }

    .table{
        overflow: auto !important;
    }
</style>


<?php
include ('_header.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/Usage.php');
require_once (__ROOT__ . '/classes/Aws.php');

$user = UserFactory::getUser ( $_SESSION ['user_name'], $_SESSION ['user_email'] );
$devices = $user->getDevices ();
$usage = new Usage ();
$aws = new Aws ();
$total_net = 0;
?>
<main>
    <div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12 col-lg-12">
            <h5>Usage: <?php echo date("jS F, Y", strtotime('first day of this month', time()))?> - <?php echo date("jS F, Y", strtotime('now')); ?></h5>
            <br/>
            <hr/>
            <br/>
            <p class="text-info">*You are provided $<?php echo count($devices) * 2; ?>  worth of download and storage free every month.*</p>
            <br/>
            <table class="table table-striped" style="border: 1px solid #f2f2f2">
                  <thead>
                    <tr>
                        <th scope="col"><strong>Name</strong></th>
                        <th scope="col"><strong>Network</strong></th>
                        <th scope="col"><strong>Storage</strong></th>
                        <th scope="col"><strong>Objects</strong></th>
                        <th scope="col"><strong>Action</strong></th>
                    </tr>
                   </thead>
                   <tbody>
 <?php

$net = 0;
$data = 0;
$objects = 0;
$total_net = 0;
$total_data = 0;
foreach ( $devices as $device ) {
        $today = Utils::dateNow ( $device->timezone );
        list ( $furl, $datetime ) = $aws->latestMotionDataUrl ( $device->uuid, $today );
        $ismp4 = strpos($furl, '.mp4') !== false;
        $usg = $usage->monthToDateUsage ( $device->uuid );
        if ($usg) {
            $net = $usg->network *2;
            $data = $usg->disk;
            $objects = $usg->objects;
            $total_net = $total_net + $net;
            $total_data = $total_data + $data;
        }
        ?>
       
                       <tr>
                           <td style="font-size: 16px;"><?php echo $device->device_name; ?></td>
                           <td style="font-size: 16px;"><?php echo Utils::formatSizeUnits($net); ?></td>
                           <td style="font-size: 16px;"><?php echo Utils::formatSizeUnits($data); ?> </td>
                           <td style="font-size: 16px;"><?php echo $objects; ?></td>
                           <td style="font-size: 16px;"><a data-toggle="tooltip" data-placement="bottom" title="More Details" href="index.php?uuid=<?php echo $device->uuid; ?>&view=<?php echo USAGE_DETAILS; ?>">
                               <span class="fa fa-external-link">Details</span>
                           </a></td>
                       </tr>
 <?php }?>
                   </tbody>
             </table>
 


  


    <p style="margin-top: 5px; margin-bottom: 1rem; font-size: 16px; margin-left: 5px; margin-right: 5px;"><strong>Total Network Usage = <?php echo Utils::formatSizeUnits($total_net);?></strong> | <strong>Total Storage Used = <?php echo Utils::formatSizeUnits($total_data); ?></strong> | <strong>Forecast for current month = $<?php echo Utils::forecast($total_net) + Utils::forecast($total_data); ?></strong></p>

        </div>
    </div>
    </div>
    
</main>


<script src="js/master.js"></script>
  
<?php include('common/add_space.php'); ?>
<?php include('_footer.php'); ?>




