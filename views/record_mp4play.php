<?php include('_header.php');

require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/classes/Aws.php');

$aws = new Aws();
$uuid=$_GET['uuid'];
$key=$_GET['key'];
$recordings = $aws->loadRecording($uuid, $key);
$mp4=array();
foreach ($recordings as $record){
    $furl = $aws->getFileUrl($record->image);
    $mp4[] = $furl;
}

?>

<link href="//vjs.zencdn.net/6.2.0/video-js.css" rel="stylesheet">
<script src="//vjs.zencdn.net/6.2.0/video.js"></script>
  <!-- If you'd like to support IE8 -->
<script src="//vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>

<link href="/css/video-js.min.css" rel="stylesheet">
<script src="/js/video.min.js"></script>
<script src="/js/jquery.min.js"></script>


<div class="container">
  
  <div class="row">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
          <b><?php echo $key; ?>:</b>
          <a href="/views/record_delete.php?key=<?php echo $key; ?>&uuid=<?php echo $uuid; ?>">
              <span class="glyphicon  glyphicon-trash"></span>
          </a>
          
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
        <video id="video0" 
              class="video-js vjs-default-skin vjs-4-3"
              width="100%"
              style="display: block;"
          >
            <source id="source0"  src="<?php echo $mp4[0]; ?>"  type="video/mp4" />
          <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that 
                 <a href="//videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
          </p>
        </video>

        <video id="video1" 
              class="video-js vjs-default-skin vjs-4-3"
              width="100%"
              style="display: block;"
         >
            <source id="source0"  src="<?php echo $mp4[1]; ?>"  type="video/mp4" />
          <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that 
                 <a href="//videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
          </p>
        </video>
        
    </div>
  </div>
</div>
<br/>

 <div class="row">
    <div class="col-sm-8 col-md-6 col-md-offset-3 col-sm-offset-2">
          <p>
           &nbsp;&nbsp;&nbsp;Playing &nbsp; <b id="playing"></b>&nbsp; of &nbsp; 
           <b><?php echo sizeof($mp4) * 5 ; ?>s</b> &nbsp;&nbsp;
           
           <button id="pause"  class="btn btn-link" type="button" onClick='repeat()'>
                    <span class="glyphicon glyphicon-repeat"></span></button>     
                    
           <button id="back" class="btn btn-link" type="button" onClick='back()'>
                    <span class="glyphicon glyphicon-step-backward"></span></button>     
           
           <button id="play"  class="btn btn-link" type="button" onClick='play()'>
                    <span class="glyphicon glyphicon-play"></span></button>   
                      
           <button id="pause"  class="btn btn-link" type="button" onClick='pause()'>
                    <span class="glyphicon glyphicon-pause"></span></button>     
                    
           <button id="forward"  class="btn btn-link" type="button" onClick='forward()'>
                    <span class="glyphicon glyphicon-step-forward"></span></button>    
                    
           <button id="forward"  class="btn btn-link" type="button" onClick='forward()'>
                    <span class="glyphicon glyphicon-forward"></span></button>                  
                   
           <button id="forward"  class="btn btn-link" type="button" onClick='mute()'>
                    <span class="glyphicon glyphicon-volume-off"></span></button>
                     
           <button id="forward"  class="btn btn-link" type="button" onClick='unmute()'>
                    <span class="glyphicon glyphicon-volume-up"></span></button>  
                    
           <button id="forward"  class="btn btn-link" type="button" onClick=''>
                    <span class="glyphicon glyphicon-share"></span></button>                     
          </p>
    </div>
</div>
 
    <div class="row">
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
    </div>

       
<script type="text/javascript">
<!--


var playerNumber = 0;
var player =  videojs("video0"); 
var player_hidden =  videojs("video1");
var current = player;
var index=0;
var playback_rate=1;


var videoSource=[
    <?php 
    foreach ($mp4 as $mp4_url){
             echo '"'.$mp4_url.'"'.", "."\n";
        }
    ?>
 ];
var videoCount=videoSource.length;

function forward(){
    if (playback_rate < 5 ) {
        playback_rate = playback_rate + 0.2;
    }
}
function back(){
    playback_rate = 1;
    index = index -1;
    if (index < 0 )index=0;
}
function repeat(){
    console.log("repeat from " + index);
    index = -1;
    playback_rate = 1;
    current.src(getNext());
    current.load();
    current.play();
}
function pause(){
    current.pause();
}
function play(){
    current.play();
}
function updatePlaying(){
    document.getElementById("playing").innerHTML = (index * 5 ) + "s" ;
}
function getNext(){
    updatePlaying();
    index++;
    console.log("To play " + index);
    return videoSource[index];
}
player.on('play', function(){
  player_hidden.src(getNext()); 
  player_hidden.load();
  console.log("loading " + index);
});
player_hidden.on('play', function(){
    player.src(getNext()); 
    player.load();
    console.log("loading " + index);
});
player.on('ended', function(){
    if (index > videoCount){
        player.pause();
        player_hidden.pause();
        return;
    }
     player.hide();  
     player_hidden.show(); 
     player_hidden.playbackRate(playback_rate); 
     player_hidden.play();
     current = player_hidden;
 });
player_hidden.on('ended', function(){
    if (index > videoCount){
        player.pause();
        player_hidden.pause();
        return;
    }
    player_hidden.hide(); 
    player.show();
    player.playbackRate(playback_rate); 
    player.play();
    current = player;
});
player.on('error', function(){
    console.log("player on error " + index);
    if (index > videoCount){
        player.pause();
        return;
    }
     player.hide();  
     player.src(getNext());
     player.load();
     player_hidden.show(); 
     player_hidden.playbackRate(playback_rate); 
     player_hidden.play();
     current = player_hidden;
});

player_hidden.on('error', function(){
    console.log("player_hidden hidden on error " + index);
    if (index > videoCount){
        player_hidden.pause();
        return;
    }
    player_hidden.hide(); 
    player_hidden.src(getNext());
    player_hidden.load();
    player.show();
    player.playbackRate(playback_rate); 
    player.play();
    current = player;
});

player_hidden.hide();
player.show();
player.play();
</script>
<?php include('_footer.php'); ?>