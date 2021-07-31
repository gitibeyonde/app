<?php include('_header.php'); ?>
<?php 
require_once(__ROOT__.'/classes/RegistryPort.php');
require_once(__ROOT__.'/classes/DeviceContext.php');

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
$pr = new RegistryPort();
list($ip, $port) = $pr->getIpAndPort($uuid);

$stream_id = mt_rand();
$context = new DeviceContext();
$context->updateDeviceContext($uuid, "live", $stream_id);
?>


<script type="text/javascript">
<!--
window.addEventListener("beforeunload", function () {
    $(window).unload(function () {
        $.ajax({
          type: 'GET',
          async: false,
          url: 'https://app.ibeyonde.com//views/livev_close.php?uuid=<?php echo $uuid; ?>&sid=<?php echo $stream_id; ?>'
        });
     });
    //$.get("https://app.ibeyonde.com//views/livev_close.php?uuid=<?php echo $uuid; ?>&sid=<?php echo $stream_id; ?>"); 
    //Gecko + IE,Webkit, Safari, Chrome
  });

//-->
</script>

    <div class="row">    
     <div class="col-sm-12 col-md-6 col-md-offset-3">
        
         <img class="img-responsive"  width="100%" id="live<?php echo $uuid; ?>" style="display: none;"
              src="//<?php echo $ip; ?>/udp/live_v.php?timezone=<?php echo $timezone; 
              ?>&quality=<?php echo $quality; ?>&user_name=<?php echo $user_name;?>&user_id=<?php echo $user_id;
              ?>&uuid=<?php echo $uuid; ?>&device_name=<?php echo $device_name; ?>&port=<?php echo $port; ?>&sid=<?php echo $stream_id; ?>&rand=<?php echo mt_rand();?>">
          </img>   
         
           <video id="video" autoplay></video>
                 
          <a href="index.php?uuid=<?php echo $uuid; ?>&device_name=<?php echo $device_name; ?>&view=<?php echo DEVICE_VIEW; ?>">
             <button type="button" class="btn btn-info btn-lg btn-block" style="align: center;"><span class="glyphicon  glyphicon-dashboard">&nbsp;<?php echo $device_name; ?>(<?php echo $uuid; ?>)</button>
          </a>
    </div>
    </div>
    

<script>
  const mediaSource = new MediaSource();
  video.src = URL.createObjectURL(mediaSource);
  mediaSource.addEventListener('sourceopen', sourceOpen, { once: true });

  function getIndex(remote_url) {
      return $.ajax({
          type: "GET",
          url: remote_url,
          async: false
      }).responseText;
  }

  function sourceOpen() {
    URL.revokeObjectURL(video.src);
    const sourceBuffer = mediaSource.addSourceBuffer('video/mp4; codecs="avc1.640028"');
    
    var index_str = getIndex("//<?php echo $ip; ?>/img/live/<?php echo $uuid; ?>/index");
    console.log("Index=" +index_str);
    var indv = index_str.split(":");

    console.log("Opening " + "//<?php echo $ip; ?>/img/live/<?php echo $uuid; ?>/" + indv[0] + ".mp4");
    // Fetch beginning of the video by setting the Range HTTP request header.
    fetch("//<?php echo $ip; ?>/img/live/<?php echo $uuid; ?>/" + indv[0] + ".mp4")
    .then(response => response.arrayBuffer())
    .then(data => {
      sourceBuffer.appendBuffer(data);
      sourceBuffer.addEventListener('updateend', updateEnd, { once: true });
    });
  }

  function updateEnd() {
    console.log("video segment is ready to play!");
    video.play();
    // Fetch the next segment of video when user starts playing the video.
    video.addEventListener('playing', fetchNextSegment, { once: true });
  }


  function fetchNextSegment() {
    var index_str = getIndex("//<?php echo $ip; ?>/img/live/<?php echo $uuid; ?>/index");
    console.log("Index=" +index_str);
    var indv = index_str.split(":");
    console.log("Fetching " + "//<?php echo $ip; ?>/img/live/<?php echo $uuid; ?>/" + indv[0] + ".mp4");
    fetch("//<?php echo $ip; ?>/img/live/<?php echo $uuid; ?>/" + indv[0] + ".mp4")
    .then(response => response.arrayBuffer())
    .then(data => {
      const sourceBuffer = mediaSource.sourceBuffers[0];
      sourceBuffer.appendBuffer(data);
      // TODO: Fetch further segment and append it.
    });
  }
</script>


<?php include('_footer.php'); ?>