<?php include('_header.php'); ?>
<?php
define ( '__ROOT__',  dirname (dirname ( __FILE__ )));
require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/Motion.php');

session_start();

if (!isset($_SESSION['user_id'])){
    echo "Unauthorized Access";
    die;
}
$role = $_SESSION ['role'];
$aws = new Aws ();
$uuid = $_GET ['uuid'];
$furl = $_GET['furl'];
$user_name = $_GET['user_name'];
$device_name=$_GET['device_name'];
$alert_id = $_GET['alert_id'];
$view = $_GET['view'];

error_log("Role=".$role);
if ($role == "USER"){
    echo "<h4> Face training is only available to subscribing accounts ! </h4>";
    exit;
}
?>
<main class="main" role="main" style="margin-top:100px" >
<div class="container"  style="padding-top: 100px;">
   <div class="row">           
               <div class="col-sm-8 col-md-8 col-md-offset-2">
                  <img id="#himage" src="<?php echo $aws->getSignedFileUrl ($furl); ?>" alt="Loading..." class="img-responsive" width="237" height="178"/>
                  <form name=nameFace method=GET action="../sql_action.php">
                      <input type=hidden name=view value="<?php echo $view; ?>" /> 
                      <input type=hidden name=action value="NameFace" /> 
                      <input type=hidden name=uuid value="<?php echo $uuid ?>" /> 
                      <input type=hidden name=user_name value="<?php echo $user_name ?>" /> 
                      <input type=hidden name=alert_id value="<?php echo $alert_id ?>" /> 
                      <input type=hidden name=device_name value="<?php echo $device_name ?>" /> 
                      <input type=hidden name=url value="<?php echo $furl ?>" /> 
                      <label> Name: </label><input type="text" name="face_name" value="" style="width: 8em; height: 2em;"/> 
                      <button type="submit" class="btn btn-default btn-sm" name="submit" value="submit">OK
                      </button>
                      <button type="submit" class="btn btn-default btn-sm"  name="submit" value="cancel">Cancel
                      </button>
                      <button type="submit" class="btn btn-default btn-sm"  name="submit" value="remove">Remove
                      </button>
                </form>
               </div>
    </div>
</div>
</main>

<?php 
include ('_footer.php'); ?>