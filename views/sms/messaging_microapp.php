<?php 
include(__ROOT__.'/views/_header.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/sms/SmsMessage.php');
require_once (__ROOT__ . '/classes/sms/SmsSend.php');
require_once (__ROOT__ . '/classes/sms/SmsMinify.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/wf/data/WfAudience.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');


$log = $_SESSION['log'] = new Log('info');

$user_id = $_SESSION['user_id'];
$submit = isset($_POST['submit']) ? $_POST['submit']: null;

$WFDB = new WfMasterDb();
$ad = new WfAudience ( $user_id );
$min = new SmsMinify();
$utils = new SmsUtils();

$DBWF = $WFDB->getWorkflows($user_id);

if ($submit == "execute") {
    $message = $_POST ['message'];
    $bot_id = $_POST ['bot_id'];
    $audience_table = $_POST ['audience_table'];
    error_log ( "messaging id is=" . $message. "table=".$audience_table );
    $auds= $ad->getAudience($audience_table);
    foreach($auds as $Lperson){
        $there_number = $Lperson['number'];
        $url = $min->createMicroAppUrlForUser($user_id, $bot_id, $there_number);
        $text = $message."  ".$url;
        error_log($there_number.", ".$text);
        try {
            SmsSend::sendMicroAppToPerson($user_id, $bot_id, $text, $Lperson);
            sleep(10);
        } catch (Exception $e) {
            error_log("FATAL:".$e->getMessge());
            sleep(10);
            SmsSend::sendMicroAppToPerson($user_id, $bot_id, $text, $Lperson);
        }
    }
}
?>
<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/messaging_menu.php'); ?>

<div class="card-image">
            <br/>
            <br/>
            <h4>Deliver App via SMS</h4>
            <br/>
            <hr/>
    <form  action="/index.php?view=<?php echo DELIVER_MICROAPP; ?>"  method="post">
       <table class="table-stripped"> 
         <tr>
          <td>
          <label>Message:</label>
          </td>
          <td>
          <textarea  class="form-control"  name="message" type="text" placeholder="Accompanying message"  value=""></textarea>
          </td>
          </tr>
         <tr>
          <td>
          <label>Select Bot:</label>
          </td>
          <td>
          <select class="form-control"  name="bot_id">
          <?php foreach( $DBWF as $wf){   ?>
            <option value=<?php echo $wf['bot_id']; ?>><?php echo $wf['name']; ?></option>
           <?php }?>
          </select>
          </td>
          </tr>
         <tr>
          <td>
          <label>Select Audience:</label>
          </td>
          <td>
          <select class="form-control" name="audience_table">
          <?php foreach( $ad->ls() as $tn){ ?>
            <option value=<?php echo $tn; ?>><?php echo $tn; ?></option>
           <?php }?>
          </select>
          </td>
          </tr>
          <tr>
          <td>
          <button name="submit" type="submit" value="execute"  class="btn btn-sim1" data-toggle="tooltip" title="Go ahead run the check">
          Send</button>
          </td>
          </tr>
          </table>
    </form>       
</div>
</div>
<?php 
include(__ROOT__.'/views/_footer.php');
?>
</body>
</html>