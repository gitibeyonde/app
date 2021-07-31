<?php include('_header.php'); ?>
<?php

require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/User.php');
require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/DeviceCert.php');
require_once (__ROOT__.'/classes/RegistryPort.php');
require_once(__ROOT__.'/classes/DeviceContext.php');

// error_reporting(E_ERROR | E_PARSE);
set_time_limit ( 5 );

$user_id = $_SESSION ['user_id'];
$user_name = $_SESSION ['user_name'];
$uuid = $_GET ['uuid'];
$device_name = $_GET ['device_name'];
$user_email = $_SESSION ['user_email'];
$thisbox = 'default';
if (isset ( $_GET ['box'] )) {
    $thisbox = $_GET ['box'];
}
$utils = new Utils ();
$check_uuid = $utils->validateDevice ( $user_name, $uuid );
if ($check_uuid != $uuid) {
    echo "Bad access";
    include ('_footer.php');
    return;
}


if ($_SESSION['role'] == 'USER') {
    echo "<br/><font color=red> Please, subscribe to get access to Share </font>";
    return;
}

$user = UserFactory::getUser ( $user_name, $user_email );
$device = $user->getDevice ( $uuid );
$devices = $user->getDevices ();
$share_name= $utils->getShareName($uuid);
?>

<div class="container"  style="padding-top: 100px;">

    <h3>Sharing lets you share motion feed</h3><hr/>

     <div class="row">
        <div class="col-sm-12 col-md-8"> 
           <?php if (strlen($share_name) > 1 ) { 
                echo '<br/><p> Share is https://app.ibeyonde.com/share.php?animate=true&id='.base64_encode($share_name."@".$uuid).'<br/><br/>';
                echo '<br/><i>Share the above url to share the correspnding feed with your friends or on your website. You can change it anytime.</i>';
            }
            else {
                echo 'Not shared.<br/>'; 
                echo '</br><i>To start sharing fill in a share password below. Share password should have only characters and digits.</i>';
            }?>
            <br/>
            <form class="form-horizontal" name=shareMotion method=GET action="sql_action.php">
                <label> Share Name : </label><input type="text" name="share_name" value="<?php echo $share_name; ?>" style="width: 6em; height: 2em;"/> 
                <input type="hidden" name="device_name" value="<?php echo $device_name; ?>"/>
                <input type=hidden name=view value="<?php echo DEVICE_SHARE ?>" /> 
                <input type=hidden name=action value="ShareMotion" /> 
                <input type=hidden name=uuid value="<?php echo $device->uuid ?>" />  
                <input type="hidden" name="box" value="<?php echo $device->box_name;?>" />
                <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                <button type="submit" class="btn btn-success"  style="position: center;background-color: #c1c19c;" type=submit name="submit" value="ShareMotion">
                   Share Motion </button>
            </form>
         </div> 
         <div class="col-sm-12 col-md-4"> 
         <div class="embed-responsive embed-responsive-4by3">
             <iframe class="embed-responsive-item" scrolling="no" frameborder="0" id="<?php echo $device->uuid; ?>0" style="display: block;"
                src="../views/motion.php?&timezone=<?php echo $device->timezone; ?>&uuid=<?php echo $device->uuid; ?>&animate=true"> </iframe>
         </div>
         </div>     
    </div>
    
    
    <div class="row">
        <br /> <br />
    </div>
   
    
    <h3>Sharing lets you share your devices with another user</h3>
    <p> The user with who you want to share your device should be a registered user. </p><hr/>
     <div class="row">
        <div class="col-sm-12 col-md-12"> 
            <form class="form-horizontal" name=shareDevice method=GET action="sql_action.php">
                <label> User Name : </label><input type=text name=share_user_name value="" />
                <label>Select Devices: </label>
                <?php foreach ( $devices as $device ) { ?>
                  <label class="radio-inline">&nbsp;&nbsp;&nbsp;<input name="<?php echo $device->uuid; ?>" type="radio" value="1"><?php echo $device->device_name; ?></label>
                <?php } ?>
                <input type="hidden" name="device_name" value="<?php echo $device_name; ?>"/>
                <input type=hidden name=action value="ShareDevice" />  
                <input type="hidden" name="box" value="<?php echo $device->box_name;?>" />
                <input type=hidden name=uuid value="<?php echo $device->uuid ?>" />  
                <input type=hidden name=view value="<?php echo DEVICE_SHARE ?>" /> 
                <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                <button type="submit" class="btn btn-success"  style="position: center;background-color: #c1c19c;" type=submit name="submit" value="ShareDevice">
                   Share Device </button>
            </form>
        
        </div>
    </div>
    
     
    <div class="row">
        <br /> <br />
              <a href="javascript:window.close();">
                <button type="button" class="btn btn-info btn-lg btn-block" style="position: center;">Close</button>
            </a>
    </div>
    
    <div class="row">
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
    </div>
 
 
</div>
<?php include('_footer.php'); ?>
