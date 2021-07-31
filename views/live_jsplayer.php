<?php include('_header.php'); ?>
<?php
require_once(__ROOT__.'/classes/RegistryPort.php'); 

$user_id=$_SESSION['user_id'];
$user_name=$_SESSION['user_name'];
$device_name=$_GET['device_name'];
$uuid=$_GET['uuid'];
$quality = "SINI";
if (isset($_GET['quality'])){
    $quality=$_GET['quality'];
}

$thisbox = 'default';
if (isset ( $_GET ['box'] )) {
    $thisbox = $_GET ['box'];
}

$pr = new RegistryPort();
list($ip, $port) = $pr->getIpAndPort($uuid);
?>

   
<script type="text/javascript">
<!--
var ifrNo<?php echo $uuid;  ?> = 0;
var ifrHidden<?php echo $uuid;  ?>;
var ifr<?php echo $uuid;  ?>;
var i = 0;
var interval = null;

function swap<?php echo $uuid;  ?>() {

   ifr<?php echo $uuid;  ?> = document.getElementById('<?php echo $uuid; ?>' + ifrNo<?php echo $uuid;  ?>);
   ifrNo<?php echo $uuid;  ?> = 1 - ifrNo<?php echo $uuid;  ?>;
   ifrHidden<?php echo $uuid;  ?> = document.getElementById('<?php echo $uuid; ?>' + ifrNo<?php echo $uuid;  ?>);
   i++;

   ifr<?php echo $uuid;  ?>.onload = null;
   ifrHidden<?php echo $uuid;  ?>.onload = function() {

       ifr<?php echo $uuid;  ?>.style.display = 'none';
       ifrHidden<?php echo $uuid;  ?>.style.display = 'block';

   }
   ifrHidden<?php echo $uuid;  ?>.src ="https://<?php echo $ip; ?>/udp/snap.php?quality=<?php echo $quality; ?>&user_name=<?php echo $user_name;?>&user_id=<?php echo $user_id;?>&uuid=<?php echo $uuid; ?>&port=<?php echo $port; ?>&ip=<?php echo $ip; ?>&rand=" + Math.random();

   if ( i > 300 ){
       clearInterval(interval);
       window.location = "index.php?view=<?php echo MOTION_DASH;?>&message=Live view timed out, reloading alert view";
   }
}
  
interval = setInterval(function () {
           swap<?php echo $uuid;  ?>();
   }, <?php echo 2500;?>)
//-->
</script>
    
    <div class="row">    
     <div class="col-sm-8 col-md-8 col-md-offset-2">
          <img id="<?php echo $uuid; ?>0" style="display:block;" width="900" height="600"
              src ="https://<?php echo $ip; ?>/udp/snap.php?quality=<?php echo $quality; ?>&user_name=<?php echo $user_name;?>&user_id=<?php echo $user_id;?>&uuid=<?php echo $uuid; ?>&port=<?php echo $port; ?>&ip=<?php echo $ip; ?>&rand=<?php echo mt_rand(); ?>"> 
          <img id="<?php echo $uuid; ?>1" style="display:none;" width="900" height="600">
      </div>
    </div>
    
    <div class="row"> 
      <div class="col-sm-8 col-md-8 col-md-offset-2">
      <a href="index.php?uuid=<?php echo $uuid; ?>&view=<?php echo DEVICE_VIEW; ?>">
         &nbsp;&nbsp;<button type="button" class="btn btn-default btn-lg btn-block">&nbsp;&nbsp;<?php echo $device_name; ?>
     </button></a>
     </div>
     </div>
     <br/>
     <div class="row"> 
      <div class="col-sm-8 col-md-8 col-md-offset-2">
      
                  <form name=deviceSnapAction method=GET action="https://<?php echo $ip; ?>/udp/device_action.php">
                       <input type=hidden name=view value="<?php echo LIVE_VIEW; ?>" /> 
                       <input type=hidden name=action value="Snap" /> 
                       <input type=hidden name=box value="<?php echo $thisbox; ?>" />
                        <input type=hidden name=uuid value="<?php echo $uuid; ?>" /> 
                        <input type=hidden name=device_name value="<?php echo $device_name; ?>" /> 
                        <input type=hidden name=port value="<?php echo $port; ?>" />
                        <input type=hidden name=user_id value="<?php echo $user_id; ?>" />
                        <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                        <input type=hidden name=quality value="<?php echo $quality; ?>"/>
                        &nbsp;&nbsp;<button type="submit" class="btn btn-primary btn-lg btn-block" type=submit name="deviceSnap" value="Snap">
                        <span class="glyphicon  glyphicon-camera">&nbsp;Snap</span></button>
               </form>
    </div>
    </div>
   
<?php include('_footer.php'); ?>
