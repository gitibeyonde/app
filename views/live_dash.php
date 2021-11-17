<?php
require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/User.php');
require_once (__ROOT__ . '/classes/Quality.php');
require_once (__ROOT__ . '/classes/VQuality.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once(__ROOT__.'/classes/RegistryPort.php');
require_once(__ROOT__.'/classes/DeviceContext.php');
require_once(__ROOT__.'/classes/CamProfile.php');

$user_id = $_SESSION ['user_id'];
$user_name = $_SESSION ['user_name'];
$thisbox = 'default';
if (isset ( $_GET ['box'] )) {
    $thisbox = $_GET ['box'];
}
$muted = 'false';
if (isset ( $_GET ['muted'] )) {
    $muted = $_GET ['muted'];
}
$pr = new RegistryPort();
$user = UserFactory::getUser ( $_SESSION ['user_name'], $_SESSION ['user_email'] );
$devices = $user->getDevices ();
$boxes = $user->getBoxes ();
$client = new Aws ();
$quality = Quality::E;
$role = $_SESSION ['role'];
if (count ( $devices ) == 0) {
    echo "<h4> You need to add  devices to your account </h4>";
    include ('_footer.php');
    exit ( 0 );
} else if (count ( $devices ) < 4) {
    $quality = Quality::C;
} else if (count ( $devices ) < 8) {
    $quality = Quality::D;
} else if (count ( $devices ) < 10) {
    $quality = Quality::E;
} else {
    $quality = Quality::F;
}

$stream_id = mt_rand();
$context = new DeviceContext();
$remoteip = urldecode($_SERVER['REMOTE_ADDR']);

$loc=LIVE_DASH;
$profile = new CamProfile();
?>
<?php include('_header.php'); ?>
<div class="container-fluid top">
	<div class="row" style="background: var(--pc);height: 60px;">
	  <h2><font color="white">Live</font></h2>
	</div>
    <div class="row">
        <?php
        foreach ( $devices as $device ) {
            if (strcmp ( $device->box_name, $thisbox ) !== 0) {
                continue;
            }
            $context->updateDeviceContext($device->uuid, "live", $stream_id);
            $today = Utils::dateNow ( $device->timezone );
            list($ip, $port) = $pr->getIpAndPort($device->uuid);

            $dev = new Device();
            $device =  $dev->loadDevice ( $device->uuid );
            $settings = (array)json_decode($device->setting);
            $video_mode = 0;

            $settings = (array)json_decode($device->setting);

            if(isset( $settings['version'])){
                $version = $settings['version'];
            }
            else {
                $version = "1.0.0";
            }
            error_log("Version =".$version);

            if(isset( $settings['video_mode'])){
                $video_mode = $settings['video_mode'];
            }
            $url = null;
            $elem = null;
            $loc_conn = false;
            if ($profile->getProfileParamValue($device->profile, CamProfile::video_mode) == "none" ){
                $elem = '<img class="embed-responsive-item"  width="432" height="324" src="/img/disabled.png"></div>';
            }
            else {
                if ($video_mode == 1){
                    /*if (strcmp($device->visibleip, $remoteip) == 0 ) {
                        $mute_str = $muted == true ? "&muted=true" : "";
                        $url = "//".$device->deviceip."/video.php?". $mute_str ."&quality=".$quality."&rand=".mt_rand();
                        $elem = '<a href="'.$url.'" target=_top> <img  width="432" height="324" height="380" src="/img/play_video.png"></a>';
                        $loc_conn = true;
                    }
                    else {
                        $url = "//app.ibeyonde.com/index.php?timezone=".$device->timezone."&uuid=".$device->uuid."&tk=".$device->token.
                        "&quality=".VQuality::K."&view=video_view&device_name="."&sid=".$stream_id.$device->device_name."&box=default&muted=true";
                    }*/
                    $url = "//app.ibeyonde.com/index.php?timezone=".$device->timezone."&uuid=".$device->uuid."&tk=".$device->token.
                    "&quality=".VQuality::K."&view=video_view&device_name="."&sid=".$stream_id.$device->device_name."&box=default&muted=true";
                    $elem = '<iframe class="embed-responsive-item"  width="432" height="324" scrolling="no" frameborder="0" style="display: block;" id="live'.
                        $device->uuid.'" src="'.$url.'"> </iframe></div>';
                }
                else {
                    /*if (strcmp($device->visibleip, $remoteip) == 0 ) {
                        $url = "http://".$device->deviceip."/stream.php?quality=high&rand=".mt_rand();
                        $loc_conn = true;
                    }
                    else {
                        $url = "https://".$ip."/udp/live_n.php?timezone=".$device->timezone."&user_name=".$user_name."&quality=".$quality.
                        "&user_id=".$user_id."&uuid=".$device->uuid.
                        "&port=".$port."&sid=".$stream_id."&tk=".$device->token."&rand=".mt_rand();
                    }*/
                    $url = "https://".$ip."/udp/live_n.php?timezone=".$device->timezone."&user_name=".$user_name."&quality=".$quality.
                    "&user_id=".$user_id."&uuid=".$device->uuid.
                    "&port=".$port."&sid=".$stream_id."&tk=".$device->token."&rand=".mt_rand();
                    $elem ='<img class="img-fluid" width="100%"  alt="Please, reload or wait for auto-reload" id="live'.$device->uuid.'" src="'.$url.'"></div>';
                }
            }
            ?>
			<div class="col-md-8 col-sm-12 col-12 col-lg-6">
                <div class="card mb-4 box-shadow">
                 <div class="card-image video-wrapper">
                     <div class="embed-responsive embed-responsive-4by3">
                       <?php echo $elem; ?>
                     </div>



                <div class="card-body ">
                    <div class="flex-container justify-content-space-around">
                         <div class="row" style="width: 100%">
                             <div class="col-6">
                                <a style="cursor: pointer;text-decoration:none;" onclick='document.getElementById("live<?php echo $device->uuid; ?>").src="<?php echo $url; ?>&reload=true"'>
                                  <font size=3 color="var(--pcd)" ><?php echo $device->device_name; ?></font><br/><font size=1 color="var(--sc)" ><?php echo $device->uuid; ?></font>
                                </a>
                             </div>

                             <div class="col-2">
                                 <a style="cursor: pointer;text-decoration:none;" href="/index.php?view=<?php echo SETTINGS_DASH; ?>&timezone=<?php echo $device->timezone; ?>&loc=<?php echo $loc;
                                          ?>&uuid=<?php echo $device->uuid; ?>&device_name=<?php echo $device->device_name; ?>&tk=<?php echo $device->token; ?>&box=<?php echo $thisbox;
                                          ?>&local=<?php
                                                if (strcmp($device->visibleip, $remoteip) == 0 ) {
                                                    echo $device->deviceip; } else { echo "None";
                                                }
                                            ?>">
                                    <span class="material-icons md-48 primary">settings</span></a>

                             </div>
                             <div class="col-2">


                                <a style="cursor: pointer;text-decoration:none;" href="//app.ibeyonde.com/index.php?timezone=<?php echo $device->timezone; ?>&loc=<?php echo LIVE_DASH; ?>&uuid=<?php echo $device->uuid; ?>&view=<?php echo LIVE_VIEW;
                                 ?>&device_name=<?php echo $device->device_name; ?>&quality=HINI&box=<?php echo $thisbox; ?>&tk=<?php echo $device->token; ?>&local=<?php
                                 if (strcmp($device->visibleip, $remoteip) == 0 ) {
                                     echo $device->deviceip; } else { echo "None";
                                 }
                                 ?>"><span class="material-icons md-48 primary">ondemand_video</span>
                                 </a>

                             </div>
                             <div class="col-2">
                                <form id="default1" name=deviceSnapAction method=GET action="//<?php echo $ip; ?>/udp/device_action.php"><input type=hidden name=server value="<?php echo $_SERVER['SERVER_NAME']; ?>"/>
                                    <input type=hidden name=view value="<?php echo LIVE_DASH; ?>" />
                                    <input type=hidden name=action value="Snap" />
                                    <input type=hidden name=box value="<?php echo $thisbox; ?>" />
                                    <input type=hidden name=uuid value="<?php echo $device->uuid; ?>" />
                                    <input type=hidden name=device_name value="<?php echo $device->device_name; ?>" />
                                    <input type=hidden name=port value="<?php echo $port; ?>" />
                                    <input type=hidden name=tk value="<?php echo $device->token; ?>" />
                                    <input type=hidden name=local value="<?php
                                     if (strcmp($device->visibleip, $remoteip) == 0 ) {
                                         echo $device->deviceip; } else { echo "None";
                                     }
                                     ?>" />
                                    <input type=hidden name=loc value="<?php echo LIVE_DASH; ?>" />
                                    <input type=hidden name=timezone value="<?php echo $device->timezone; ?>" />
                                    <input type=hidden name=user_id value="<?php echo $user_id; ?>" />
                                    <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                                    <input type=hidden name=role value="<?php echo $role; ?>" />
                                    <a style="cursor: pointer;text-decoration:none;" onClick='document.getElementById("default1").submit();'>
                                    <span class="material-icons md-48 primary">add_a_photo</span>
                                    </a>
                                </form>
                             </div>
                       </div><!--  row -->
                   </div>
                 </div>
             </div>
         </div>


        <?php }?>
      </div>

 </div>

<?php include('common/box_bar.php'); ?>

<?php include('common/add_space.php'); ?>

<?php include('common/add_space.php'); ?>

<script type="text/javascript">
<!--

<?php
$count = 1;
$all_local = true;
foreach ( $devices as $device ) {
    if (strcmp ( $device->box_name, $thisbox ) !== 0) {
        continue;
    }

    $settings = (array)json_decode($device->setting);
    $video_mode = 0;

    if(isset( $settings['video_mode'])){
        $video_mode = $settings['video_mode'];
    }
    $url = null;
    $elem = null;
    if ($video_mode != 1){
        list($ip, $port) = $pr->getIpAndPort($device->uuid);
        if (strcmp($device->visibleip, $remoteip) == 0 ) {
            $url = "http://".$device->deviceip."/stream.php?quality=high&rand=".mt_rand();
        }
        else {
            $url = "//".$ip."/udp/live_n.php?timezone=".$device->timezone."&user_name=".$user_name."&quality=".$quality.
                    "&user_id=".$user_id."&uuid=".$device->uuid.
                    "&port=".$port."&sid=".$stream_id."&tk=".$device->token."&rand=".mt_rand();
        }
        $url = "//".$ip."/udp/live_n.php?timezone=".$device->timezone."&user_name=".$user_name."&quality=".$quality.
                "&user_id=".$user_id."&uuid=".$device->uuid.
                "&port=".$port."&sid=".$stream_id."&tk=".$device->token."&rand=".mt_rand();
        $elem = '<img   width="432" height="324" alt="Please, reload or wait for auto-reload" id="live'.$device->uuid.'" src="'.$url.'">';
    }
    $timeInterval = 300000 + 10000 * $count;

    if ($video_mode != 1){
?>
    setInterval(function(){
        document.getElementById("live<?php echo $device->uuid; ?>").src = '<?php echo $url; ?>' + new Date().getTime();
    }, <?php echo $timeInterval; ?>);

    window.addEventListener("beforeunload", function () {
            $.ajax({
              type: 'GET',
              async: false,
              url: '//app.ibeyonde.com//views/live_close.php?uuid=<?php echo $device->uuid; ?>&sid=<?php echo $stream_id; ?>'
            });
        //Gecko + IE,Webkit, Safari, Chrome
      });
    <?php } else { ?>

   window.addEventListener("beforeunload", function () {
            $.ajax({
              type: 'GET',
              async: false,
              url: '//app.ibeyonde.com//views/livev_close.php?uuid=<?php echo $device->uuid; ?>&sid=<?php echo $stream_id; ?>'
            });
        //Gecko + IE,Webkit, Safari, Chrome
      });
<?php
     }
    $count = $count + 1;
}

if (!$all_local){
?>

//setTimeout(function(){ window.location.href='/index.php?view=<?php echo MAIN_VIEW ?>&box=default&message=Live+view+timed+out'}, 600000);

<?php } ?>
//-->
</script>

<?php include('_footer.php'); ?>
