<?php
include ('_header.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/Usage.php');
require_once (__ROOT__ . '/classes/Aws.php');

$devices = Device::loadAllDevices();
$usage = new Usage ();
$aws = new Aws ();
$total_net = 0;
?>
<div class="container">
    <div class="row">
        <center>
            <h2>Month to Date Usage</h2>
        </center>
        <center>
            <h4> <?php echo date("jS F, Y", strtotime('first day of this month', time())).' to '.date("jS F, Y", strtotime('now')); ?></h4>
        </center>
        <hr />
        <p>&nbsp; &nbsp; Data storage and uploads are provided free of cost. Data download is charged @ $2/GB.</p>
        <hr />
    </div>
    <div class="row">
    <?php

$net = 0;
$data = 0;
$objects = 0;
$total_net = 0;
foreach ( $devices as $device ) {
        $today = Utils::dateNow ( $device->timezone );
        list ( $furl, $datetime ) = $aws->latestMotionDataUrl ( $device->uuid, $today );
        $usg = $usage->monthToDateUsage ( $device->uuid );
        if ($usg) {
            $net = $usg->network;
            $data = $usg->disk;
            $objects = $usg->objects;
            $total_net = $total_net + $net;
        }
        ?>
        <div class="col-sm-4 col-md-4">
            <table>
                <thead>
                    <tr>
                        <td>- Device -</td>
                        <td>- Usage -</td>
                    </tr>
                </thead>
                <tr>
                    <td><img src="<?php echo $furl; ?>" alt="Loading ..." class="img-responsive" width="180"></td>
                    <td>Download = <b><?php echo Utils::formatSizeUnits($net); ?> </b><br /> Storage = <b><?php echo Utils::formatSizeUnits($data); ?> </b><br /> Objects = <b><?php echo $objects; ?> </b><br />
                    </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                </tr>
                <tr>
                    <td colspan=2><a href="index.php?uuid=<?php echo $device->uuid; ?>&view=<?php echo USAGE_DETAILS; ?>"> &nbsp;&nbsp;
                            <button type="button" class="btn btn-default btn-block" style="position: center;"><?php echo $device->device_name; ?>
                     </button>
                    </a></td>
                </tr>
            </table>
        </div>
    <?php }?>
</div>
    <div class="row">
        <br /> <br />
        <center>
            <h4> Total Network Usage = <?php echo Utils::formatSizeUnits($total_net); ?></h4>
        </center>
        <center>
            <h4> Forecast = <?php echo Utils::forecast($total_net); ?> $</h4>
        </center>
        <br /> <br />
    </div>
</div>
<?php include('_footer.php'); ?>
