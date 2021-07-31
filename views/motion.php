<!DOCTYPE html>
<html lang="en">
<head>
<title>IbeyondE</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet"  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link  href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/headercss.css">

<script src='https://vjs.zencdn.net/7.6.0/video.js'></script>

<link href="https://vjs.zencdn.net/7.6.0/video-js.css" rel="stylesheet">
<!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
<script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
</head>
<?php 
define ( '__ROOT__', dirname ( dirname ( __FILE__ )));
require_once(__ROOT__ .'/classes/Aws.php');
require_once(__ROOT__.'/classes/Utils.php');
$uuid=$_GET['uuid'];
$timezone = $_GET['timezone'];
$animate = true;
if (isset($_GET['animate'])){
    $animate = $_GET ['animate'];
}
$muted=false;
if(isset( $_GET['muted'])){
    $muted = $_GET['muted'];
}
$datetime = Utils::datetimeNow ( $timezone);
$client = new Aws();
$utils = new Utils();

$ivs = $client->latestMotionDataUrls($uuid);
$utils->publishNetwork($uuid, date( 'Y/m/d H:i:s e', $datetime), 15360);
$ismp4 = false;
foreach (array_reverse($ivs) as $iv) {
    list($furl, $time) = $iv;
    $ismp4 = strpos($furl, '.mp4') !== false;
    break;
}

if ($ismp4) { ?>
<video id="video0" 
              class="video-js vjs-default-skin vjs-4-3"
              width="100%"
              height="500"
              preload='auto' 
              style="display: block;"
              <?php  if ($muted == true ) { ?> muted="muted" autoplay="true" <?php } ?>
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
              preload='auto' 
              style="display: block;"
              <?php  if ($muted == true ) { ?> muted="muted" autoplay="true" <?php } ?>
          >
          <source id="source1"  src="https://udp1.ibeyonde.com/video/loading.mp4"  type="video/mp4" />
          <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that 
                 <a href="//videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
          </p>
 </video>
         
<script type="text/javascript">
<!--

var player =  videojs("video0"); 
var player_hidden =  videojs("video1");
player_hidden.hide();

var videoSource = new Array();
<?php
$i=0;
foreach (array_reverse($ivs) as $iv) {
    list($furl, $time) = $iv;
    echo "videoSource[$i]='$furl';";
    $i++;
} 
echo "var videoCount = $i;"
?>
i=0;
function dynamicMedia(){
	i++;
	if (i == (videoCount-1)){
		i = 0;
	}
	return videoSource[i];
}

<?php if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') == true || strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') == true || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') == true) { ?>

var player =  videojs("video0");
var preloader =  videojs("video1");
preloader.hide();

console.log("PRELOADER");

player.on('play', function(){
  preloader.src(dynamicMedia() ); 
  preloader.load();
});

player.on('ended', function(){
     player.src(dynamicMedia() );
     player.load(); 
     player.play();
 });

player.on('error', function(){
     player.src(dynamicMedia() );  
     player.load(); 
     player.play();
 });

preloader.on('error', function(){
      player.src(dynamicMedia() );
      player.load();
      player.play();
 });
<?php } else { ?>

console.log("HIDDEN");

var playerNumber = 0;
var player =  videojs("video0"); 
var player_hidden =  videojs("video1");
player_hidden.hide();

player.on('play', function(){
  player_hidden.src(dynamicMedia() ); 
  player_hidden.load();
});
player_hidden.on('play', function(){
    player.src(dynamicMedia() ); 
    player.load();
});
player.on('ended', function(){
     player.hide();
     player_hidden.show();
     player_hidden.play();
 });
player_hidden.on('ended', function(){
    player_hidden.hide();
    player.show();
    player.play();
});

player.on('error', function(){
     player_hidden.hide();
     player.show();
     player.src(dynamicMedia());  
     player.load(); 
     player.play();
 });

player_hidden.on('error', function(){
     player.hide();
     player_hidden.show();
     player_hidden.src(dynamicMedia() ); 
     player_hidden.load(); 
     player_hidden.play();
 });

<?php } ?>
-->
</script>
<?php } else { ?>
        <div id="carousel-<?php echo $uuid; ?>" class="carousel slide carousel-fade" data-ride="carousel"  data-interval=1500 data-pause="hover">
             
             <div class="carousel-inner">
                   <?php 
                   $i=0;
                   foreach (array_reverse($ivs) as $iv) {
                       list($furl, $time) = $iv;
                       if ($i==0){ ?>
                    <div class="item active">
                    <?php  } else { ?>
                    <div class="item">
                    <?php  } ?>
                            <img class="img-responsive" width="100%" src="<?php echo $furl; ?>" alt="<?php echo $time; ?>" />
                        
                       <?php if ($i==0){ ?>
                        <div class="carousel-caption">
                                <h1>^</h1>
                        </div>
                        <?php } ?>
                    </div>
                   <?php  $i++;
                   } ?>
             </div>
        </div>
   </div>
<?php }?>