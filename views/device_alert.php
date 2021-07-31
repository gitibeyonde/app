<?php
include('_header.php'); 
require_once(__ROOT__.'/classes/UserFactory.php');
require_once(__ROOT__.'/classes/AlertRaised.php');
require_once(__ROOT__.'/classes/Aws.php');
require_once(__ROOT__.'/classes/Utils.php');
require_once(__ROOT__.'/classes/Face.php');
$user_name=$_SESSION['user_name'];
$uuid=$_GET['uuid'];
$device_name=$_GET['device_name'];
$timezone=$_GET['timezone'];
$thisbox=$_GET['box'];
$aws = new Aws();
$face = new Face();
$ar = new AlertRaised();
?>
<main>
<div class="container"  style="padding-top: 100px;">

 <div class="row">
    <div class="col-sm-12 col-md-3">
     <strong>Alerts</strong>
   </div>
</div>
<div class="row">  
    <?php 
     $alerts = $ar->loadAllDeviceAlerts($uuid);
    ?>
        <div class="col-sm-8 col-md-4 col-lg-4">
        <br/>
        
        <?php 
            if (count ( $alerts ) == 0) {
                echo "<b><font color=red> No alerts for ".$device_name."/".$uuid."</font></b><br/>";
            }
            else {
                foreach ($alerts as $alert){
                    error_log("Created ".$alert->created. " Timezone =".$timezone);
                    $date = DateTime::createFromFormat("Y-m-d H:i:s", $alert->created); //2018-07-19 09:20:44 2018-07-23 10:50:20
                    ?>
                	
      				<table>
                    <tr>
                    <td>
                    <span><?php if ($alert->image != null){
                               if ( $alert->type == AlertRaised::FACE_RECOGNIZED ){ ?>
                                    <form class="form-horizontal" name=nameFaceDetected method=GET action="views/face_name.php">
                                        <button class="btn btn-default" style="border: none; background: none; padding: 0; outline: none;">
                                          <img src="<?php echo $aws->getSignedFileUrl($alert->image); ?>" width=70 >
                                        </button>
                                        <input type=hidden name=user_name value="<?php echo $user_name ?>" />
                                        <input type=hidden name=device_name value="<?php echo $device_name ?>" />
                                       <input type=hidden name=alert_id value="<?php echo $alert->id ?>" />
                                       <input type=hidden name=uuid value="<?php echo $uuid ?>" />
                                        <input type=hidden name=view value="<?php echo DEVICE_ALERTS ?>" />
                                       <input type=hidden name=furl value="<?php echo $alert->image ?>" />
                                    </form>
                                <?php }
                                else  if ( $alert->type == AlertRaised::FACE_DETECTED ){ ?>
                                    <form class="form-horizontal" name=nameFaceDetected method=GET action="views/face_name.php">
                                        <button class="btn btn-default" style="border: none; background: none; padding: 0; outline: none;">
                                          <img src="<?php echo $aws->getSignedFileUrl($alert->image); ?>" width=70 >
                                        </button>
                                        <input type=hidden name=user_name value="<?php echo $user_name ?>" />
                                        <input type=hidden name=device_name value="<?php echo $device_name ?>" />
                                       <input type=hidden name=alert_id value="<?php echo $alert->id ?>" />
                                       <input type=hidden name=uuid value="<?php echo $uuid ?>" />
                                        <input type=hidden name=view value="<?php echo DEVICE_ALERTS ?>" />
                                       <input type=hidden name=furl value="<?php echo $alert->image ?>" />
                                    </form>
                                <?php  }
                                else {
                                   echo "<img src=".$aws->getSignedFileUrl($alert->image)." width=70 >"; 
                                }
                    }
                    else if ($alert->type == AlertRaised::HUMID_HIGH || $alert->type == AlertRaised::HUMID_LOW) {
                        echo "<img src=/img/humidity.png width=40 >";
                    }
                    else if ($alert->type == AlertRaised::TEMP_HIGH || $alert->type == AlertRaised::TEMP_LOW) {
                        echo "<img src=/img/temperature.png width=40 >";
                    }
                    else {
                        echo "<img src=/img/yes.png width=20 >";
                    } 
                    ?>
                    <a href="/index.php?view=for_image_header&uuid=<?php echo $uuid ?>&date=<?php echo date_format($date, 'Y/m/d'); ?>&time=<?php echo date_format($date, 'H'); ?>" >
                     	<?php echo date_format($date, 'Y/m/d H:i:s'); ?> 
                     </a>
                     </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                    <a href="/index.php?view=for_image_header&uuid=<?php echo $uuid ?>&date=<?php echo date_format($date, 'Y/m/d'); ?>&time=<?php echo date_format($date, 'H'); ?>" >
                    	<font size=3 color=grey><?php echo AlertRaised::getAlertString($uuid, $alert->type, $alert->value, $alert->comment); ?> </font>
                     </a>
                    </td>
                    </tr>
                 </table>
                <?php } 
            }
          ?>
    </div>
      
</div>
 
    <div class="row">
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
    </div>
    
</div>
</main>
<?php 
include('_footer.php'); ?>