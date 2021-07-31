<?php include('_header.php'); ?>

<?php 
require_once(__ROOT__.'/classes/RegistryPort.php');
require_once(__ROOT__.'/classes/DeviceContext.php');
require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/classes/Quality.php');
require_once (__ROOT__ . '/classes/VQuality.php');
require_once (__ROOT__ . '/classes/Mjpeg.php');
require_once (__ROOT__ . '/classes/Mp4.php');
require_once(__ROOT__.'/classes/CamProfile.php');

$user_id=$_SESSION['user_id'];
$user_name=$_SESSION['user_name'];
$device_name=$_GET['device_name'];
$timezone=$_GET['timezone'];
$uuid=$_GET['uuid'];
$box=$_GET['box'];
$token=$_GET['tk'];
$local=$_GET['local'];

$loc=LIVE_DASH;
if(isset( $_GET['loc'])){
    $loc = $_GET['loc'];
}

$muted=false;
if(isset( $_GET['muted'])){
    $muted = $_GET['muted'];
}
$role = $_SESSION ['role'];
$pr = new RegistryPort();
list($ip, $port) = $pr->getIpAndPort($uuid);
$dev = new Device();
$device =  $dev->loadDevice ( $uuid );
$settings = (array)json_decode($device->setting);

if(isset( $settings['version'])){
    $version = $settings['version'];
}
else {
    $version = "1.0.0";
}
error_log("Version =".$version);

$quality="HINI";
$zoom="none";
if (version_compare($version, "1.0.6") >=0){
    error_log("zoom New version ");
    $quality="HINI";
}
else {
    error_log("zoom Old version ");
    $quality="HINI";
    $zoom = "notset";
}

$video_mode = 0;

if(isset( $settings['video_mode'])){
    $video_mode = $settings['video_mode'];
}



$context = new DeviceContext();
$stream_id = mt_rand();
$context->updateDeviceContext($uuid, "live", $stream_id);

$profile = new CamProfile();
$url = null;
$elem = null;
if ($profile->getProfileParamValue($device->profile, CamProfile::video_mode) == "none" ){
    $elem = '<img class="embed-responsive-item" width="100%" src="/img/disabled.png"></div>';
}
else {
    if ($video_mode == 1){
        /*if (strcmp($local, "None")==0 ) 
        {
            $url = "https://app.ibeyonde.com/index.php?timezone=".$device->timezone."&uuid=".$device->uuid."&quality=".VQuality::K."&view=video_view&device_name=".
                $device->device_name."&rand=".mt_rand()."&tk=".$device->token."&box=default";
        }
        else {
            $mute_str = $muted == true ? "&muted=true" : "";
            $url = "//".$device->deviceip."/video.php?quality=HINI".$mute_str ."&rand=".mt_rand();       
        }*/
        $url = "https://app.ibeyonde.com/index.php?timezone=".$device->timezone."&uuid=".$device->uuid."&quality=".VQuality::K."&view=video_view&device_name=".
            $device->device_name."&rand=".mt_rand()."&tk=".$device->token."&box=default";
        $elem = '<div class="embed-responsive embed-responsive-4by3">'.
           '<iframe  class="embed-responsive-item" scrolling="no" frameborder="0" style="display: block;" width="100%" id="live'.$device->uuid.'" src="'.$url.'"> </iframe></div>';
    }
    else {
        /*if (strcmp($local, "None")==0 ) {
            $url = "https://".$ip."/udp/live_n.php?timezone=".$device->timezone."&user_name=".$user_name."&quality=".$quality."&user_id=".$user_id."&uuid=".$device->uuid.
                    "&port=".$port."&sid=".$stream_id."&tk=".$device->token."&rand=".mt_rand();
        }
        else {
            $url = "http://".$device->deviceip."/stream.php?quality=high&rand=".mt_rand();
        }*/
        $url = "https://".$ip."/udp/live_n.php?timezone=".$device->timezone."&user_name=".$user_name."&quality=".$quality."&user_id=".$user_id."&uuid=".$device->uuid.
            "&port=".$port."&sid=".$stream_id."&tk=".$device->token."&rand=".mt_rand();
        $elem = '<img  width="100%" alt="Please, reload or wait for auto-reload" id="live'.$device->uuid.'" src="'.$url.'">';
    }
}

function getRecordingColor($uuid, $video_mode){
    return $video_mode == 1 ?  (Mjpeg::isRecording($uuid) ? "red" : "black") : (Mp4::isRecording($uuid) ? "red" : "black");
}

function getRecordingState($uuid, $video_mode){
    return $video_mode == 1 ?  (Mjpeg::isRecording($uuid) ? "&nbsp;Stop" : "&nbsp;Record") : (Mp4::isRecording($uuid) ? "&nbsp;Stop" : "&nbsp;Record");
}
?>


<style>

.box-shadow {
            
            transition: 1s;
    box-shadow: 4px 4px 10px 1px #ccc;   
    
        }

        
.card:hover{
            
    box-shadow: 8px 8px 10px 1px #ccc;
        }
    
.card .card-image{
    overflow: hidden;
    -webkit-transform-style: preserve-3d;
    -moz-transform-style: preserve-3d;
    -ms-transform-style: preserve-3d;
    -o-transform-style: preserve-3d;
    transform-style: preserve-3d;
}


.remote-button1{
        color: white;
        background: #02ccba;
        border-radius: .2rem !important;
        border: 1px solid #02ccba !important;
        transition: 1s;
        box-shadow: 8px 8px 10px 1px #ccc;
        margin: 1px;
    }
    
    .remote-button1:hover{
        color: white;
        transition: 1s;
        box-shadow: 4px 4px 10px 1px #ccc;
        margin: 1px;
         
    }

    .link-button2{
        color: #365e88;
        background: transparent;
        outline: none;
        font-size: 14px;
    }

        .after1:after{
        content:'';
        display: block;
        width: 130px;
        height: 3px;
        background: linear-gradient(to right, #6f6f09, orange) !important;
        text-align: center;
        margin-top: 5px;

        }

        .after2:after{
        content:'';
        display: block;
        width: 54px;
        height: 3px;
        background: linear-gradient(to right, #6f6f09, orange) !important;
        text-align: center;
        margin-top: 5px;

        }

    .flex-container1{
        display: flex;
        flex-direction: row;
        flex-flow: wrap;
        justify-content: flex-start;
    }

.grid {
  display: flex;           /* 1 */
  flex-flow: row wrap;
  justify-content: center;
  
}

.up, .down {
  flex: 0 0 100%;                 /* 2 */
  text-align: center;             /* 2 */
}

.left, .right {
  flex: 1 0 1%;                   /* 3 */
  display: flex;
}

.left  { justify-content: flex-end; }
.right { justify-content: flex-start; }

.plus {}
.minus {}



</style>


<main class="mt-5" role="main">
    <div class="album py-5 bg-none">
    <div class="container-fluid">

<div class="row">

    <div class="col-md-2 col-sm-0 col-0 col-lg-3">
    </div>
    <div class="col-md-8 col-sm-12 col-12 col-lg-6">
                   
					<div class="card mb-4 box-shadow">
                       <div style="cursor: pointer;" class="card-image" data-toggle="collapse" data-target="#zoom">
                           <iframe name="donothing" style="display:none;"></iframe>
                           <?php echo $elem; ?>
                        </div>
                        
                        <div class="card-body">
                            
                            <div class="flex-container">
                                
                                <small class="mb-2 after1" style="cursor: pointer;" class="text-muted"><?php echo $device_name; ?> : <?php echo $uuid; ?></small>
                                 <small style="cursor: pointer;"><a href="/index.php?view=<?php echo SETTINGS_DASH; ?>&timezone=<?php echo $device->timezone; ?>&loc=<?php echo $loc; 
                                    ?>&uuid=<?php  echo $device->uuid; ?>&device_name=<?php echo $device->device_name; ?>&tk=<?php echo $device->token; ?>&box=<?php echo $thisbox; 
                                    ?>&local=<?php
                                        if (strcmp($device->visibleip, $remoteip) == 0 ) { 
                                            echo $device->deviceip; } else { echo "None"; 
                                        }
                                    ?>">
                                    <img src="/img/settings.png" width="20"/></a>
                               </small>

                        <span class="mr-auto"></span>

                        <small style="cursor: pointer;" class="text-muted fa fa-arrows fa-1x" data-toggle="collapse" data-target="#zoom"></small>

                            </div>
                                
                        <div class="flex-container1">
                            <a style="cursor: pointer;text-decoration:none;" onclick='document.getElementById("live<?php echo $uuid; ?>").src="<?php echo $url; ?>&reload=true"'><small class="after2 text-muted mr-3">Reload</small>
                            </a>
                            <form id="recordform" name=RecordToggleAction method=GET action="https://<?php echo $ip; ?>/udp/device_action.php"  target="donothing">
                        <input type=hidden name=action value="RecordToggle" /> 
                        <?php include('common/live_player_input.php'); ?>
                                <a style="cursor: pointer;text-decoration:none;" onClick='document.getElementById("recordform").submit(); recordToggle();'><small id="RecordToggleSpan" class="text-muted after2 mr-3"><?php echo getRecordingState($uuid, $video_mode); ?></small></a>
                            
                        </form>
                                
                       <a style="cursor: pointer;text-decoration:none;" href="index.php?view=<?php echo RECORD_MANAGE; ?>&uuid=<?php echo $uuid; ?>&video_mode=<?php echo $video_mode;?>&device_name=<?php $device_name; ?>"><small class="after1 text-muted mr-3">Manage Recording</small>
                        </a>
                    </div>



                        <?php if ($zoom != "notset") { ?>


                            <div class="grid collapse" id="zoom">
                                         <form style="margin-bottom: 0px;" class="up" name=moveUp method=GET action="https://<?php echo $ip; ?>/udp/device_action.php" target="donothing">
                                            <input type=hidden name=action value="Zoom" /> 
                                            <input type=hidden name=area value="moveUp" /> 
                                            <?php include('common/live_player_input.php'); ?>
                                            <button type="submit" class="btn btn-sm remote-button1" name="moveUp" value="moveUp"><span class="fa fa-arrow-up"></span>
                                            </button>
                                        </form>



                                         <form style="margin-bottom: 0px;" class="left" name="moveLeft" method=GET action="https://<?php echo $ip; ?>/udp/device_action.php" target="donothing">
                                            <input type=hidden name=action value="Zoom" /> 
                                            <input type=hidden name=area value="moveLeft" /> 
                                            <?php include('common/live_player_input.php'); ?>
                                            <button type="submit" class="btn btn-sm remote-button1" name="moveLeft" value="moveLeft"><span class="fa fa-arrow-left"></span>
                                            </button>
                                        </form>



                                         <form style="margin-bottom: 0px;" class="plus" name=zoomIn method=GET action="https://<?php echo $ip; ?>/udp/device_action.php" target="donothing">
                                            <input type=hidden name=action value="Zoom" /> 
                                            <input type=hidden name=area value="zoomIn" />
                                            <?php include('common/live_player_input.php'); ?>
                                            <button type="submit" class="btn btn-sm remote-button1" name="zoomIn" value="zoomIn"><span style="font-weight: 2" class="fa fa-plus"></span>
                                            </button>
                                        </form>

                                        <form style="margin-bottom: 0px;" class="minus" name=zoomOut method=GET action="https://<?php echo $ip; ?>/udp/device_action.php" target="donothing">
                                                <input type=hidden name=action value="Zoom" /> 
                                                <input type=hidden name=area value="zoomOut" /> 
                                                <?php include('common/live_player_input.php'); ?>
                                                <button type="submit" class="btn btn-sm remote-button1" name="zoomOut" value="zoomOut"><span class="fa fa-minus"></span>
                                                </button>
                                            </form>
                                        <form style="margin-bottom: 0px;" class="right" name=moveRight method=GET action="https://<?php echo $ip; ?>/udp/device_action.php" target="donothing">
                                            <input type=hidden name=action value="Zoom" /> 
                                            <input type=hidden name=area value="moveRight" /> 
                                            <?php include('common/live_player_input.php'); ?>
                                            <button type="submit" class="btn btn-sm remote-button1" name="moveRight" value="moveRight"><span class="fa fa-arrow-right"></span>
                                            </button>
                                        </form>

                                         <form style="margin-bottom: 0px;" class="down" name=moveDown method=GET action="https://<?php echo $ip; ?>/udp/device_action.php" target="donothing">
                                            <input type=hidden name=action value="Zoom" /> 
                                            <input type=hidden name=area value="moveDown" /> 
                                            <?php include('common/live_player_input.php'); ?>
                                            <button type="submit" class="btn btn-sm remote-button1" name="moveDown" value="moveDown"><span class="fa fa-arrow-down"></span>
                                            </button>
                                        </form>
                                    </div>
                        </div>
        </div>
    </div>
        </div>  
    
    <?php } 
    else { ?>
    
      <p> Zoom is available in version 1.0.6 and above, please update your device from Settings</p>
    
    <?php }
    ?> 
    

    <?php if (strpos($device->capabilities, "SPEAKER") !== false) { ?>
            
    
<script src="js/audiodisplay.js"></script>
<script src="js/recorder.js"></script>
<script src="js/recordMain.js"></script>

<script type="text/javascript">

var callBackUrl = "https://<?php  echo $ip; ?>/udp/send_blob.php";
var timezone="<?php echo $device->timezone; ?>";
var user_name="<?php echo $user_name; ?>";
var user_id="<?php echo $user_id; ?>";
var uuid="<?php echo $device->uuid; ?>";
var port="<?php echo $port; ?>";
var tk="<?php echo $device->token; ?>";

function announceToggle(context) {
    var cur_value = document.getElementById("AnnounceToggleSpan").innerHTML;
    if (cur_value == "&nbsp;Mic Off") {
        document.getElementById("AnnounceToggleSpan").innerHTML = "&nbsp;Mic On";
        document.getElementById("AnnounceToggleSpan").style.color = "red";
        recordTimer = setInterval(function(){
            startRecording(context);
        }, 2000); 
    }
    else {
        document.getElementById("AnnounceToggleSpan").innerHTML = "&nbsp;Mic Off";
        document.getElementById("AnnounceToggleSpan").style.color = "black";
        clearInterval(recordTimer);
        stopRecording(context);
    }
}

</script>

     <div class="row">    
       <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 col-sm-offset-1 col-md-offset-2 col-lg-offset-3">      
          <div class="row">  
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                   <canvas id="analyser" height="44" style="background: lightgrey;"></canvas>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                	 <form name="AnnounceToggleAction" method=GET action="https://<?php echo $ip; ?>/udp/device_action.php"  target="donothing">
                    <input type=hidden name=action value="AnnounceToggle" />
                    <?php include('common/live_player_input.php'); ?>
                        <button type="submit" class="btn btn-block btn-lg"  style="background-color: #bed4ff;" type=submit 
                            name="AnnounceToggle" value="AnnounceToggle" onClick="announceToggle(this)"
                            >
                                <span class="glyphicon  glyphicon-record"  id="AnnounceToggleSpan" style="color: black;">&nbsp;Mic Off</span>
                        </button>
                    </form>
            </div>
        </div>
       </div>
     </div>
    <?php } ?>
    
    
    <div class="row">
        <br /> <br />
        <br /> <br />
    </div>
    
   
<?php include('common/add_space.php'); ?>
 
<script type="text/javascript">

function recordToggle() {
    var cur_value = document.getElementById("RecordToggleSpan").innerHTML;
    if (cur_value == "&nbsp;Record") {
        document.getElementById("RecordToggleSpan").innerHTML = "&nbsp;Stop";
        document.getElementById("RecordToggleSpan").classList.remove("text-muted");
        document.getElementById("RecordToggleSpan").classList.add("text-danger");
        
    }
    else {
        document.getElementById("RecordToggleSpan").innerHTML = "&nbsp;Record";
        document.getElementById("RecordToggleSpan").classList.remove("text-danger");
        document.getElementById("RecordToggleSpan").classList.add("text-muted");
    }
}

function shutdownLive(vm){
    if (vm == 1){
        $.ajax({
            type: 'GET',
            async: true,
            url: '//app.ibeyonde.com/views/livev_close.php?uuid=<?php echo $uuid; ?>&sid=<?php echo $stream_id; ?>'
          });
        console.log("Calling livev_close.php");
        }
    else {
        $.ajax({
            type: 'GET',
            async: true,
            url: '//app.ibeyonde.com/views/live_close.php?uuid=<?php echo $uuid; ?>&sid=<?php echo $stream_id; ?>'
          });
        console.log("Calling live_close.php");
    }
}

function unloadLive(vm){
    if (vm == 0){
        console.log("setting unload for mjpeg");
        $(window).on("unload", function(e) {
            shutdownLive(0);
     	});
    }
    else {
       console.log("setting unload for video");
       $(window).on("unload", function(e) {
           shutdownLive(1);
    	});
    }
}

function shutdownRecord(){
    $.ajax({
        type: 'GET',
        async: true,
        url: '//<?php echo $ip; ?>/udp/device_action.php?server=<?php echo $_SERVER['SERVER_NAME']; ?>&view=<?php echo LIVE_VIEW; ?>&action=StopRecord&uuid=<?php echo $uuid;
                ?>&device_name=<?php echo $device_name; ?>&mode=<?php echo $video_mode; ?>&box=<?php echo $box; ?>&tk=<?php echo $token; ?>&local=<?php echo $local;
                ?>&loc=<?php echo LIVE_VIEW; ?>&timezone=<?php echo $timezone; ?>&port=<?php echo $port; ?>&user_id=<?php echo $user_id; ?>&user_name=<?php echo $user_name;
                ?>&role=<?php echo $role; ?>'
      });
    console.log("Calling record Shutdown");
}


<?php if ($video_mode != 1){ ?>
    unloadLive(0);
    setTimeout(function(){
            shutdownLive(0);
            shutdownRecord();
            window.location.href='/index.php?view=<?php echo MAIN_VIEW ?>&box=default&message=Live+view+timed+out';
    }, 600000);

    setInterval(function(){
        console.log("reloading ...");
        document.getElementById("live<?php echo $uuid; ?>").src = "<?php echo $url; ?>&reload=true&t="+ new Date().getTime();
   }, 30000);      
       
    <?php } else { ?>
    
    unloadLive(1);
    setTimeout(function(){
            shutdownLive(1);
            shutdownRecord();
            window.location.href='/index.php?view=<?php echo MAIN_VIEW ?>&box=default&message=Live+view+timed+out'
     }, 600000);

 <?php } ?>


</script>  
        </div></div></main>
    
<?php include('_footer.php'); ?>
</html>
