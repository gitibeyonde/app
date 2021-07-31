<?php
include ('_header.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/Usage.php');
require_once (__ROOT__ . '/classes/Aws.php');

$uuid = $_GET ['uuid'];
$device_name = $_GET ['device_name'];
$user = UserFactory::getUser ( $_SESSION ['user_name'], $_SESSION ['user_email'] );
$utils = new Utils ();
$usage = new Usage ();
$aws = new Aws ();
$total_net = 0;
$tv = $utils->getTags ( $uuid, 100 );

?>
<div class="row">
    <div class="col-sm-12 col-md-3">
        <strong>Tags</strong>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-3">
        <br />
            <?php
                if (count ( $tv ) == 0) {
                    echo "<font color=orange>No tags " . $device_name . "</font>";
                } else {
                    echo "<h5><u><font color=green>" . $device_name . "</font></u></h5>";
                    foreach ( $tv as $tag ) {
                        echo '<center><font color=red>' . $tag ['name'] . '</font></center>';
                        $date = DateTime::createFromFormat('d/m/Y - H:i:s', $tag['time']);
                        echo '<a href=/index.php?view=for_image_header&uuid='.$uuid.'&date='.$date->format('Y/m/d').'&time='.$date->format('H').'>';
                        echo '<center><img src="' . $aws->getSignedFileUrl ( $tag ['url'] ) . '" alt="Loading ..." class="img-responsive" width="180"></center>';
                        echo '</a>';
                    }
                }
            ?>
        <br />
    </div>
</div>
<div class="row">
    <br /> <br /> <br /> <br />
</div>
<?php include('_footer.php'); ?>
