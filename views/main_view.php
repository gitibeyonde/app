<?php
// error_log(__ROOT__);
require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/User.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once(__ROOT__.'/classes/CamProfile.php');

// error_reporting(E_ERROR | E_PARSE);

set_time_limit ( 5 );

$user_name = $_SESSION ['user_name'];
$user_email = $_SESSION ['user_email'];
$user = UserFactory::getUser ( $user_name, $user_email );
$thisbox = 'default';
if (isset ( $_GET ['box'] )) {
    $thisbox = $_GET ['box'];
}
$animate = 'true';
if (isset($_GET['animate'])){
    $animate = $_GET ['animate'];
}
$devices = $user->getDevices ();
$boxes = $user->getBoxes ();
$client = new Aws ();
$cap = null;


if (count ( $devices ) == 0) {
    include('_header.php');
    echo "<body><main></br>";
    echo "&nbsp;&nbsp;&nbsp;<emp><i> You need to add  devices to your account. Buy them from <a href='https://site.ibeyonde.com/shop/'>here</a></i> </emp>";
    echo "</br></br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><a target='_blank' href='http://ibeyonde.com/cloudcam-setup/'>CloudCam and CloudBell setup manual</a></b>";
    echo "</br></br>";
    echo "<a href='http://ibeyonde.com/cloudcam-setup/'><img src='https://site.ibeyonde.com/wp-content/uploads/2020/05/cloudcam_parts.001-1024x768-2.jpeg'></a>";
    include ('_footer.php');
    exit ( 0 );
}


include('_header.php');

$remoteip = urldecode($_SERVER['REMOTE_ADDR']);
$loc=MAIN_VIEW;
$profile = new CamProfile();
?>
<script type="text/javascript">
<?php
if (strpos($cap, 'CAMERA') !== false) {
    foreach ( $devices as $device ) {
        if (strcmp ( $device->box_name, $thisbox ) !== 0) {
            continue;
        }
        $today = Utils::dateNow ( $device->timezone );
        ?>
    var ifrNo<?php echo $device->uuid;  ?> = 0;
    var ifrHidden<?php echo $device->uuid;  ?>;
    var ifr<?php echo $device->uuid;  ?>;

    function swap<?php echo $device->uuid;  ?>() {

       ifr<?php echo $device->uuid;  ?> = document.getElementById('<?php echo $device->uuid; ?>' + ifrNo<?php echo $device->uuid;  ?>);
       ifrNo<?php echo $device->uuid;  ?> = 1 - ifrNo<?php echo $device->uuid;  ?>;
       ifrHidden<?php echo $device->uuid;  ?> = document.getElementById('<?php echo $device->uuid; ?>' + ifrNo<?php echo $device->uuid;  ?>);

       ifr<?php echo $device->uuid;  ?>.onload = null;
       ifrHidden<?php echo $device->uuid;  ?>.onload = function() {

           ifr<?php echo $device->uuid;  ?>.style.display = 'none';
           ifrHidden<?php echo $device->uuid;  ?>.style.display = 'block';

       }
       ifrHidden<?php echo $device->uuid;  ?>.src ="views/motion.php?muted=false&timezone=<?php echo $device->timezone; ?>&uuid=<?php echo $device->uuid; ?>&animate=<?php echo $animate; ?>";
    }
    <?php } ?>

    setInterval(function () {
        <?php

    foreach ( $devices as $device ) {
        if (strcmp ( $device->box_name, $thisbox ) !== 0) {
            continue;
        }
        ?>
               swap<?php echo $device->uuid;  ?>();
           <?php } ?>
    }, <?php if ($animate=='true') { echo "60000"; } else { echo "30000"; } ?>);

<?php }?>

</script>

<body>
<div class="container-fluid top">
	<div class="row" style="background: var(--pc);height: 60px;">
	  <h2><font color="white">Device List</font></h2>
	</div>

	<div class="row">

       <?php
        foreach ( $devices as $device ) {
            if (strcmp ( $device->box_name, $thisbox ) !== 0) {
                continue;
            }
        ?>
			<div class="col-md-8 col-sm-12 col-12 col-lg-6">
                <div class="card mx-auto mb-4 box-shadow">
                    <div class="card-image video_wrapper">
                        <iframe class="embed-responsive-item"  style="width:100%;height:100%;position:absolute;left:0px;top:0px;" frameborder="0" width="640" height="480" id="<?php echo $device->uuid; ?>0" style="display: block"
                                    src="views/motion.php?muted=false&timezone=<?php echo $device->timezone; ?>&uuid=<?php echo $device->uuid; ?>&animate=<?php echo $animate; ?>"> </iframe>
                        <iframe class="embed-responsive-item"  style="width:100%;height:100%;position:absolute;left:0px;top:0px;" frameborder="0" width="640" height="480" id="<?php echo $device->uuid; ?>1" style="display: none"></iframe>
                    </div>

                    <div class="card-body ">
                       <div class="row" style="width: 100%">
                         <div class="col-6">
                          <font size=4 color="var(--pcd)" ><?php echo $device->device_name; ?></font><br/><font size=1 color="var(--sc)" ><?php echo $device->uuid; ?></font>
                         </div>
                         <div class="col-2">
                          	<a style="cursor: pointer;text-decoration:none;" href="/index.php?view=<?php echo SETTINGS_DASH; ?>&timezone=<?php echo $device->timezone; ?>&loc=<?php echo $loc;
                                    ?>&uuid=<?php  echo $device->uuid; ?>&device_name=<?php echo $device->device_name; ?>&tk=<?php echo $device->token;
                                    ?>&box=<?php echo $thisbox; ?>&local=<?php
                                        if (strcmp($device->visibleip, $remoteip) == 0 ) {
                                            echo $device->deviceip; } else { echo "None";
                                        }
                                    ?>">
                                    <span class="material-icons md-48 primary">settings</span></a>

                         </div>
                         <div class="col-2">

                             <a style="cursor: pointer;text-decoration:none;" href="index.php?uuid=<?php echo $device->uuid; ?>&view=<?php echo HISTORY_VIEW; ?>">
                                    <span class="material-icons md-48 primary">image_search</span>
                             </a>

                         </div>
                         <div class="col-2">
                            <?php if ($profile->getProfileParamValue($device->profile, CamProfile::video_mode) != "none" ){ ?>
                               <a style="cursor: pointer;text-decoration:none;" href="index.php?timezone=<?php echo $device->timezone; ?>&loc=<?php echo $loc; ?>&uuid=<?php echo $device->uuid; ?>&view=<?php echo LIVE_VIEW
                                 ?>&device_name=<?php echo $device->device_name; ?>&quality=HINI&box=<?php echo $thisbox; ?>&tk=<?php echo $device->token; ?>&local=<?php
                                    if (strcmp($device->visibleip, $remoteip) == 0 ) {
                                        echo $device->deviceip; } else { echo "None";
                                    }
                                ?>"><span class="material-icons md-48 primary">ondemand_video</span>
                               </a>
                            <?php } ?>
                         </div>
                       </div>
                  </div>
                </div>
            </div>
		<?php }  ?>

     </div>
</div>
<?php include('common/add_space.php'); ?>

<?php include('common/add_space.php'); ?>

<?php include('_footer.php'); ?>

</body>
</html>
