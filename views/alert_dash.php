<?php
include('_header.php'); 
require_once(__ROOT__.'/classes/UserFactory.php');
require_once(__ROOT__.'/classes/AlertRaised.php');
require_once(__ROOT__.'/classes/Aws.php');
require_once(__ROOT__.'/classes/Utils.php');
require_once(__ROOT__.'/classes/Face.php');
$user_name=$_SESSION['user_name'];
$user = UserFactory::getUser($user_name, $_SESSION['user_email']);
if (isset($_GET ['box'])){
    $thisbox = $_GET ['box'];
}
else {
    $thisbox="default";
}
$aws = new Aws();
$face = new Face();
$devices = $user->getDevices();
$ar = new AlertRaised();

if ($_SESSION['role'] == 'USER') {
?>

<main>
<div class="container-fluid top"> 

<h1 style="color: #5e9ca0;"> Alerts Dashboard</h1>
<h2 style="color: #2e6c80;">What is there on this dashboard ?</h2>
<p>Alert dashboard displays the alerts that are being generated from your devices. The alerting mechanism includes many sub services that can be configured independantly for each device: </p>

<h2 style="color: #2e6c80;">Some of the mirco-services that are available are:</h2>
<ol style="list-style: none; font-size: 14px; line-height: 32px; font-weight: bold;">
<li style="clear: both;">Face detection and Face recognition</li>
<li style="clear: both;">Temperature and Humidity, breach of set limits</li>
<li style="clear: both;">License plate recognition and number capture</li>
<li style="clear: both;">Template match, patterns matching a predefind image snippet</li>
<li style="clear: both;">Crowd warning, in case number of people in camera view exceed preset number</li>
<li style="clear: both;">People counting, number of people crossing</li>
<li style="clear: both;">Threat detection, violent movements, weapons or threatening voices</li>
</ol>

<h2 style="color: #2e6c80;">How do I get this enabled ?</h2>

<p>Alerts are charged as you use them. You need to setup your subscription to enable the alert dashboard. Write to info@ibeyonde.com for further info. </p>

</div>
</main>
<?php 
return;
}
?>
<main>
<div class="container">


 <div class="row" text-align="right">
    <div class="col-md-6 text-right"></div>
    <div class="col-md-6 text-right"><?php 
    if ($aws->checkTrainingData($user_name)){
     ?>
        <form name=RemoveTraining method=GET action="../sql_action.php">
                <input type=hidden name=action value="RemoveTraining" />
                
                 <div class="modal fade" id="RemoveTrainingModal" tabindex="-1" role="dialog" aria-labelledby="RemoveTrainingModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">Remove Training Data</h4>
                            </div>
                            <div class="modal-body">
                                <p>Remove training data will permanetly remove the training data that you have created.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <button type="submit" class="btn"  data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#RemoveTrainingModal" style="position: center;background-color: #dbcafd;" 
                     type=submit name="RemoveTraining" value="RemoveTraining">
                    <span class="glyphicon  glyphicon-arrow-trash">&nbsp; Remove Training Data </span>
                </button>
            </form>
            
  <?php
    }
    else {
        echo "No face training data. Help ?";
    }?>
    </div>
</div>
<div class="row">  
    <?php 
    foreach ( $devices as $device ) {
        $alerts = $ar->loadDeviceAlerts($device->uuid);
        error_log(print_r($alerts, true));
        ?>
     <div class="col-sx-12 col-sm-6 col-md-4 col-lg-4">
        <div class="card card-block">
            <br/>
            <b><font color='#355e87'> <?php echo $device->device_name."/".$device->uuid; ?>
                 <a href="index.php?uuid=<?php echo $device->uuid; ?>&device_name=<?php echo $device->device_name ?>&timezone=<?php echo $device->timezone; ?>&view=<?php echo SETTINGS_DASH; ?>&box=<?php echo $thisbox; ?>"> 
                   &nbsp;&nbsp;&nbsp;<img src="/img/settings.png" width="20"/><br/>
                </a></font>
            </b>
            <br/>
       </div>
       <br/>
        
    
        <?php 
       if (count ( $alerts ) == 0) {
                echo "<br/><b><font color=red> No alerts</font></b><br/>";
        }
        else {
            foreach ($alerts as $alert){
                error_log("Created ".$alert->created. " Timezone". $device->timezone);
                $date = DateTime::createFromFormat("Y-m-d H:i:s", $alert->created); //2018-07-19 09:20:44 2018-07-23 10:50:20
                ?>
                <div class="card bg-light">
                <table>
                  <tr>
                  <td colspan="2">
                        <a href="/index.php?view=<?php echo HISTORY_VIEW; ?>&uuid=<?php echo $device->uuid ?>&date=<?php echo date_format($date, 'Y/m/d'); ?>&time=<?php echo date_format($date, 'H'); ?>" >
                        <?php echo date_format($date, 'Y/m/d H:i:s'); ?> 
                        </a>
                  </td>
                  </tr>
                  <tr>
                    <td>
                        <span><?php if ($alert->image != null){ 
                                if ( $alert->type == AlertRaised::FACE_RECOGNIZED ){ ?>
                                    <form class="form-horizontal" name=nameFaceDetected method=GET action="views/face_name.php">
                                        <button class="btn btn-default" style="border: none; background: none; padding: 0; outline: none;">
                                          <img src="<?php echo $aws->getSignedFileUrl($alert->image); ?>" width=70 >
                                        </button>
                                        <input type=hidden name=user_name value="<?php echo $user_name ?>" />
                                        <input type=hidden name=device_name value="<?php echo $device->device_name ?>" />
                                       <input type=hidden name=alert_id value="<?php echo $alert->id ?>" />
                                       <input type=hidden name=uuid value="<?php echo $device->uuid ?>" />
                                        <input type=hidden name=view value="<?php echo ALERT_DASH ?>" />
                                       <input type=hidden name=furl value="<?php echo $alert->image ?>" />
                                    </form>
                                <?php }
                                else  if ( $alert->type == AlertRaised::FACE_DETECTED ){ ?>
                                    <form class="form-horizontal" name=nameFaceDetected method=GET action="views/face_name.php">
                                        <button class="btn btn-default" style="border: none; background: none; padding: 0; outline: none;">
                                          <img src="<?php echo $aws->getSignedFileUrl($alert->image); ?>" width=70 >
                                        </button>
                                        <input type=hidden name=user_name value="<?php echo $user_name ?>" />
                                        <input type=hidden name=device_name value="<?php echo $device->device_name ?>" />
                                       <input type=hidden name=alert_id value="<?php echo $alert->id ?>" />
                                       <input type=hidden name=uuid value="<?php echo $device->uuid ?>" />
                                        <input type=hidden name=view value="<?php echo ALERT_DASH ?>" />
                                       <input type=hidden name=furl value="<?php echo $alert->image ?>" />
                                    </form>
                                <?php  }
                                else {
                                   echo "<img src=".$aws->getSignedFileUrl($alert->image)." width=70 >"; 
                                }
                        }
                        else if ($alert->type == AlertRaised::HUMID_HIGH || $alert->type == AlertRaised::HUMID_LOW) {
                            echo "<img src=/img/humidity.png width=50 >"; 
                        }
                        else if ($alert->type == AlertRaised::TEMP_HIGH || $alert->type == AlertRaised::TEMP_LOW) {
                            echo "<img src=/img/temperature.png width=50 >";
                        }
                        else {
                            echo "<img src=/img/yes.png width=30 >";
                        } 
                        ?>
                		</span>
                    </td>
                    <td>
                		<a href="/index.php?view=<?php echo HISTORY_VIEW; ?>&uuid=<?php echo $device->uuid ?>&date=<?php echo date_format($date, 'Y/m/d'); ?>&time=<?php echo date_format($date, 'H'); ?>" >
                    	<font size=3 color=grey><?php echo AlertRaised::getAlertString($device->uuid, $alert->type, $alert->value, $alert->comment); ?> </font>
                		</a>
                    </td>
                    </tr>
                </table>
                </div>
          <?php  } 
         echo "<h5><center><a href=/index.php?view=".DEVICE_ALERTS."&uuid=".$device->uuid."&device_name=".$device->device_name.">".$device->device_name." more..</a></center></h5><br/><br/><br/>";
      }   ?>
        
    </div> 
    <?php }?>
      
</div>

    <div class="row">
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
    </div>
    
</div>
</main>
<?php 
include('_footer.php'); ?>