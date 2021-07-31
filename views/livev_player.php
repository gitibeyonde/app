<?php 
require_once(__ROOT__.'/classes/RegistryPort.php');
require_once(__ROOT__.'/classes/DeviceContext.php');

$token = $_GET['tk'];
$user_id=$_SESSION['user_id'];
$user_name=$_SESSION['user_name'];
$device_name=$_GET['device_name'];
$timezone=$_GET['timezone'];
$uuid=$_GET['uuid'];
$box=$_GET['box'];
$quality="VLO";
if (isset($_GET['quality'])){
    $quality=$_GET['quality'];
}

$muted=true;
if(isset( $_GET['muted'])){
    $muted = $_GET['muted'];
}
$pr = new RegistryPort();
list($ip, $port) = $pr->getIpAndPort($uuid);

$stream_id = mt_rand();
$context = new DeviceContext();
$context->updateDeviceContext($uuid, "live", $stream_id);

$agent_type="nice";
if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') == true || strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') == true || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') == true) { 
    $agent_type="restricted";
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>

<script src='https://vjs.zencdn.net/7.6.0/video.js'></script>

<link href="https://vjs.zencdn.net/7.6.0/video-js.css" rel="stylesheet">
<!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
<script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>

    <div class="row">    
     <div class="col-sm-12 col-md-6 col-md-offset-3">
          <div class="col-sm-6 col-md-6 col-md-offset-3">
            <video id="video0" 
                          class="video-js vjs-default-skin vjs-4-3"
                          width="100%"
                          height="500"
                          style="display: block;"
                          preload="auto"
                          muted 
                          <?php if ($agent_type=="restricted") { ?> 
                          		 autoplay controls 
                          <?php } else { ?>
                          		 autoplay
                          <?php }?>
                      >
                      <source id="source0"  src=""  type="video/mp4" />
                      <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that 
                             <a href="//videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                      </p>
            </video>
            <video id="video1" 
                          class="video-js vjs-default-skin vjs-4-3"
                          width="100%"
                          height="500"
                          style="display: block;"
                          preload="auto" 
                          muted
                          <?php if ($agent_type=="nice") { ?> 
                          		 autoplay
                          <?php }?>
                      >
                      <source id="source1"  src=""  type="video/mp4" />
                      <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that 
                             <a href="//videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                      </p>
             </video>
    </div>
    </div>
 </div>
    
<script type="text/javascript">
<!--
<!--

function refresh() {
    var ping = $.ajax({
        type: "GET",
        url: "//<?php echo $ip; ?>/udp/live_v.php?timezone=<?php echo $timezone; 
            ?>&quality=<?php echo $quality; ?>&user_name=<?php echo $user_name;?>&user_id=<?php echo $user_id;
            ?>&uuid=<?php echo $uuid; ?>&device_name=<?php echo $device_name; ?>&port=<?php echo $port; ?>&sid=<?php echo $stream_id; 
            ?>&rand=<?php echo mt_rand();?>&tk=<?php echo $token; ?>",
        async: true
    }).responseText;
    console.log("REFRESHING ");
}

refresh();


interval = setInterval(function(){
       refresh();
}, 60000);

window.addEventListener("focus", function () {
    refresh();
}, false);



var media_url="//udp1.ibeyonde.com/video/loading.mp4";
var ts=0;

function dynamicMedia(){
	var url="";
    jQuery.ajax({
        type: "GET",
        url: "//<?php echo $ip; ?>/udp/video_next.php?ts=" + ts + "&uuid=<?php echo $uuid; ?>",
        async: false,
        success: function(list_str) {
            var vv = list_str.split("-");
            url = vv[0];
            ts = vv[1];
            console.log("url=" + url + ", ts=" + ts);
        }
    });
    media_url="//<?php echo $ip; ?>/" + url + "?ts=" + ts; 
    return media_url;
}

<?php if ($agent_type=="restricted") { ?>

console.log("IOS");

var player =  videojs("video0"); 
var player_hidden =  videojs("video1");
player_hidden.hide(); 
player.show();

player.on('play', function(){
    console.log("player.on('play'");
});

player.on('ended', function(){
    console.log("player.on('ended'");
    player.src(dynamicMedia() );
    player.load();
 });

player.on('error', function(){
     console.log("player.on('error'");
     player.src(dynamicMedia() );
     player.load();
 });

<?php } else { ?>

console.log("CHROME");

var player =  videojs("video0"); 
var player_hidden =  videojs("video1");
player_hidden.hide();
player.show();

player.on('play', function(){
  //console.log("player.on('play'");
  player_hidden.src(dynamicMedia() ); 
  player_hidden.load();
});
player_hidden.on('play', function(){
    //console.log("player_hidden.on('play'");
    player.src(dynamicMedia() ); 
    player.load();
});
player.on('ended', function(){
     //console.log("player.on('ended'");
     player.hide();
     player_hidden.show();
     player_hidden.play();
 });
player_hidden.on('ended', function(){
    //console.log("player_hidden.on('ended'");
    player_hidden.hide();
    player.show();
    player.play();
});

player.on('error', function(){
    //console.log("player.on('error'");
     player_hidden.hide();
     player.show();
     player.src(dynamicMedia());  
     player.load(); 
     player.play();
 });

player_hidden.on('error', function(){
    //console.log("player_hidden.on('error'");
     player.hide();
     player_hidden.show();
     player_hidden.src(dynamicMedia() ); 
     player_hidden.load(); 
     player_hidden.play();
 });

<?php } ?>


//-->
</script>  
