<?php 

include('_header.php'); 
require_once(__ROOT__.'/classes/UserFactory.php');
require_once(__ROOT__.'/classes/RegistryPort.php');
require_once(__ROOT__.'/classes/User.php');
require_once(__ROOT__ . '/classes/Aws.php');
require_once(__ROOT__.'/classes/Utils.php');
error_reporting(E_ERROR | E_PARSE);

set_time_limit(5);
$user_id=$_SESSION['user_id'];
$user_name=$_SESSION['user_name'];
$user = UserFactory::getUser($_SESSION['user_name'], $_SESSION['user_email']);
$devices = $user->getDevices();
$client = new Aws();
if (count($devices)==0){
    echo "<h4> You need to add  devices to your account </h4>";
    include('_footer.php'); 
    exit(0);
}
else if (count($devices)==1){
    header("Location: index.php?view=".DEVICE_VIEW."&uuid=".$devices[0]->uuid);
}
$pr = new RegistryPort();
?>
    
<script type="text/javascript">
<!--

<?php foreach ($devices as $device){ 
    $today=Utils::dateNow($device->timezone);
    list($ip, $port) = $pr->getIpAndPort($device->uuid);
    ?>
var ifrNo<?php echo $device->uuid;  ?> = 0;
var ifrHidden<?php echo $device->uuid;  ?>;
var ifr<?php echo $device->uuid;  ?>;
var interval = null;
var i = 0;
    
function swap<?php echo $device->uuid;  ?>() {

   ifr<?php echo $device->uuid;  ?> = document.getElementById('<?php echo $device->uuid; ?>' + ifrNo<?php echo $device->uuid;  ?>);
   ifrNo<?php echo $device->uuid;  ?> = 1 - ifrNo<?php echo $device->uuid;  ?>;
   ifrHidden<?php echo $device->uuid;  ?> = document.getElementById('<?php echo $device->uuid; ?>' + ifrNo<?php echo $device->uuid;  ?>);
   i++;
   
   ifr<?php echo $device->uuid;  ?>.onload = null;
   ifrHidden<?php echo $device->uuid;  ?>.onload = function() {

       ifr<?php echo $device->uuid;  ?>.style.display = 'none';
       ifrHidden<?php echo $device->uuid;  ?>.style.display = 'block';

   }
   ifrHidden<?php echo $device->uuid;  ?>.src ="https://<?php echo $ip; ?>/udp/snap.php?quality=<?php echo $quality; ?>&user_name=<?php echo $user_name;?>&user_id=<?php echo $user_id;?>&uuid=<?php echo $uuid; ?>&port=<?php echo $port; ?>&rand=" + Math.random();
       
   if ( i > 300 ){
       clearInterval(interval);
       window.location = "index.php?view=<?php echo MOTION_DASH;?>&message=Live view timed out";
   }
}
<?php } ?>
  
interval = setInterval(function () {
    <?php 
    $i=0;
    foreach ($devices as $device){ ?>
           swap<?php echo $device->uuid;  ?>();
       <?php 
        $i++;
    } 
    ?>
}, <?php echo 1800*$i;?>)
//-->
</script>
    
          <div class="row">
        <?php
        foreach ($devices as $device){
            $today=Utils::dateNow($device->timezone);
            list($ip, $port) = $pr->getIpAndPort($device->uuid);
         ?>
             <div class="col-sm-4 col-md-4">
                      <img id="<?php echo $device->uuid; ?>0" style="display:block;" width="432" height="324"
                           src ="https://<?php echo $ip; ?>/udp/snap.php?quality=<?php echo $quality; ?>&user_name=<?php echo $user_name;?>&user_id=<?php echo $user_id;?>&uuid=<?php echo $uuid; ?>&port=<?php echo $port; ?>&rand=<?php echo mt_rand(); ?>"
                           >
                       <img id="<?php echo $device->uuid; ?>1" style="display:none;" width="432" height="324">
            
                        <a href="index.php?timezone=<?php echo $device->timezone; ?>&box=<?php echo $thisbox; ?>&uuid=<?php echo $device->uuid; ?>&quality=<?php echo Quality::B; ?>&view=<?php echo LIVE_VIEW; ?>&device_name=<?php echo $device->device_name; ?>"
                            style="position:absolute; top:0; left:0; display:inline-block; width:200px; height:200px; z-index:5;"></a>
                       <a href="index.php?uuid=<?php echo $device->uuid; ?>&quality=<?php echo Quality::B; ?>&device_name=<?php echo $device->device_name; ?>&view=<?php echo DEVICE_VIEW; ?>">
                          <button type="button" class="btn btn-default  btn-lg btn-block" style="position:center;"><?php echo $device->device_name; ?>
                    </button></a>
                  <form name=deviceSnapAction method=GET action="https://<?php echo $ip; ?>/udp/device_action.php">
                       <input type=hidden name=view value="<?php echo LIVE_DASH; ?>" /> 
                       <input type=hidden name=action value="Snap" /> 
                       <input type=hidden name=box value="<?php echo $device->box_name; ?>" />
                        <input type=hidden name=uuid value="<?php echo $device->uuid; ?>" /> 
                        <input type=hidden name=device_name value="<?php echo $device->device_name; ?>" /> 
                        <input type=hidden name=port value="<?php echo $port; ?>" />
                        <input type=hidden name=user_id value="<?php echo $user_id; ?>" />
                        <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                        <input type=hidden name=quality value="<?php echo Quality::A; ?>"/>
                        &nbsp;&nbsp;<button type="submit" class="btn btn-primary btn-lg btn-block" type=submit name="deviceSnap" value="Snap">
                        <span class="glyphicon  glyphicon-camera">&nbsp;Snap</button>
               </form>
         </div>
        <?php }?>
      </div>
    
    <div class="row">
        <br/>
        <br/>
        <br/>
        <br/>
    </div>
    
<?php include('_footer.php'); ?>
