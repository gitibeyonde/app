<?php
include(__ROOT__.'/views/_header.php');

require_once (__ROOT__ . '/classes/sms/SmsSend.php');
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/EmailUtils.php');
require_once (__ROOT__ . '/classes/sms/SmsMessage.php');
include_once(__ROOT__ . '/classes/wf/data/WfAudience.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/data/WfPayment.php');


$log = $_SESSION['log'] = new Log ("info");
$user_id = $_SESSION['user_id'];

$SU = new SmsUtils();
$smsmessage = new SmsMessage();
$AD = new WfAudience($user_id);

error_log("Message Post is = " . isset($_POST['submit']));
$WFP = new WfPayment();
$balance = $WFP->getThisMonthBalance($user_id);
$action=null;
$message_id=null;
$message = null;
if (isset ( $_POST ['submit'] )) {
    $action = $_POST ['submit'];
    error_log ( "Action is " . $action );
    if ($action == "survey-sms") {
        $message_id = $_POST ['message_id'];
        error_log ( "messaging id is=" . $message_id );
    } 
    else if ($action == "execute-sms") {
        if ($balance > 0){
            $message_id = $_POST ['message_id'];
            $audience_table = $_POST['audience_table'];
            error_log ( "messaging id is=" . $message_id );
            $template = $smsmessage->getMessage($user_id, $message_id)['template'];
            error_log ( "template is=" . $template );
            $audience = $AD->getAudience($audience_table);
            foreach($audience as $Lperson){
                $otp6 = mt_rand(100000, 999999);
                $Lperson['otp'] = $otp6;
                $log->trace("Template is ". $template." and data is ".SmsWfUtils::flatten($Lperson, true));
                try {
                    SmsSend::sendTemplateToPerson($user_id, "survey", $message_id, $Lperson);
                    sleep(2);
                } catch (Exception $e) {
                    error_log("FATAL:".$e->getMessge());
                    sleep(5);
                    SmsSend::sendTemplateToPerson($user_id, "survey", $message_id, $Lperson);
                }
            }
            
        }
        else {
            $_SESSION['message'] = "No balance to exceute survey";
        }
    } 
    else if ($action == "survey-email") {
            $message_id = $_POST ['message_id'];
            error_log ( "messaging id is=" . $message_id );
   } 
   else if ($action == "execute-email") {
        if ($balance > 0){
            $message_id = $_POST ['message_id'];
            $audience_table = $_POST['audience_table'];
            error_log ( "messaging id is=" . $message_id );
            $template = $smsmessage->getMessage($user_id, $message_id)['template'];
            error_log ( "template is=" . $template );
            $audience = $AD->getAudience($audience_table);
            $EM = new EmailUtils();
            foreach($audience as $Lperson){
                $otp6 = mt_rand(100000, 999999);
                $Lperson['otp'] = $otp6;
                $log->trace("Template is ". $template." and data is ".SmsWfUtils::flatten($Lperson, true));
                try {
                    $EM->sendTemplateToPerson($user_id, "survey", $message_id, $Lperson);
                    sleep(2);
                } catch (Exception $e) {
                    error_log("FATAL:".$e->getMessge());
                    sleep(5);
                    $EM->sendTemplateToPerson($user_id, "survey", $message_id, $Lperson);
                }
            }
            
        }
        else {
            $_SESSION['message'] = "No balance to exceute survey";
        }
    } 
}
$log->trace("Tables =" . SmsWfUtils::flatten($AD->ls()));
$messages = $smsmessage->getMessage($user_id);
$emailmessages = $smsmessage->getEmailMessage ( $user_id );
?>
<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/messaging_menu.php'); ?>

            <br/>
            <h4>SMS Surveys</h4>
  			<p>Current Balance is Rs. <?php echo $balance; ?></p>
            <?php if ($balance <=0 ){?>
                 <form action="/index.php"  method="get">
                    <input type=hidden name=view value="<?php echo USER_CHARGE; ?>">
            		<label>To send the messages you need money in your account, </label>
                    <button type="submit" name="submit" value="add" style="background: transparent; border: 0;">Add Balance.</button>
                </form>
            <?php }?>
          <table class="table table-striped">
            <?php
            if ($messages == null || count($messages) == 0) {
                echo "<tr><td colspan=5><font style='color: #3862c6;'>No text message templates found, create one !</font></td></tr>";
            } else {
           ?> 
           <tr>
           <th>Name</th>
           <th>Template</th>
           <th>Response </th>
           <th>Run</th>
          </tr>
           <?php    foreach ($messages as $map) { ?>
            <tr width="100%">
            <td><?php echo $map['name']; ?></td>
            <td><?php echo $map['template']; ?></td>
            <td><?php echo $map['response']; ?><?php echo $map['id']; ?></td>
              <td width="20px">
                <form  class="form-inline" action="/index.php?view=<?php echo SMS_SURVEY; ?>"  method="post">
                    <input type=hidden name=message_id value=<?php echo $map['id']; ?>>
                    <button name="submit" type="submit" value="survey-sms"  class="btn btn-sim1""  data-toggle="tooltip" 
                    title="Check the final mapping">Open</button>
                </form>
              </td>
            </tr>
            <?php if ($action == "survey-sms"  && $message_id == $map['id']){ ?>
            <tr>
               <td colspan=5>
                    <form  class="form-inline" action="/index.php?view=<?php echo SMS_SURVEY; ?>"  method="post">
                   <table>
                     <tr>
                      <td>
                      <label for="tid">Select Audience:</label>
                      </td>
                      <td>
                      <select class="form-control" id="audience_table" name="audience_table">
                      <?php foreach( $AD->ls() as $tn){ ?>
                        <option value=<?php echo $tn; ?>><?php echo $tn; ?></option>
                       <?php }?>
                      </select>
                      </td>
                      <td>
                       <input type=hidden name="message_id" value="<?php echo $map['id']; ?>">
                      <button name="submit" type="submit" value="execute-sms" class="btn btn-sim1"
                            data-toggle="tooltip" title="Go ahead run the check">Run</button>
                      </td>
                      </tr>
                      </table>
                </form>
                  <td>
             </tr>
             <?php } ?>
        <?php } } ?>
        </table>
        
            <br/>
            <h4>EMAIL Surveys</h4>
 
        
          <table class="table table-striped">
            <?php
            if ($emailmessages == null || count($emailmessages) == 0) {
                echo "<tr><td colspan=5><font style='color: #3862c6;'>No email message templates found, create one !</font></td></tr>";
            } else {
           ?> 
           <tr>
           <th>Name</th>
           <th>Subject</th>
           <th>Template</th>
           <th>Response </th>
           <th>Run</th>
          </tr>
           <?php    foreach ($emailmessages as $map) { ?>
            <tr width="100%">
            <td><?php echo $map['name']; ?></td>
            <td><?php echo $map['subject']; ?></td>
            <td><?php echo $map['template']; ?></td>
            <td><?php echo $map['response']; ?><?php echo $map['id']; ?></td>
              <td width="20px">
                <form  class="form-inline" action="/index.php?view=<?php echo SMS_SURVEY; ?>"  method="post">
                    <input type=hidden name=message_id value=<?php echo $map['id']; ?>>
                    <button name="submit" type="submit" value="survey-email"  class="btn btn-sim1""  data-toggle="tooltip" 
                    title="Check the final mapping">Open</button>
                </form>
              </td>
            </tr>
            <?php if ($action == "survey-email"  && $message_id == $map['id']){ ?>
            <tr>
               <td colspan=5>
                    <form  class="form-inline" action="/index.php?view=<?php echo SMS_SURVEY; ?>"  method="post">
                   <table>
                     <tr>
                      <td>
                      <label for="tid">Select Audience:</label>
                      </td>
                      <td>
                      <select class="form-control" id="audience_table" name="audience_table">
                      <?php foreach( $AD->ls() as $tn){ ?>
                        <option value=<?php echo $tn; ?>><?php echo $tn; ?></option>
                       <?php }?>
                      </select>
                      </td>
                      <td>
                       <input type=hidden name="message_id" value="<?php echo $map['id']; ?>">
                      <button name="submit" type="submit" value="execute-email" class="btn btn-sim1"
                            data-toggle="tooltip" title="Go ahead run the check">Run</button>
                      </td>
                      </tr>
                      </table>
                </form>
                  <td>
             </tr>
             <?php } ?>
        <?php } } ?>
        </table>
</div>
         
</main>

<?php include(__ROOT__.'/views/_footer.php'); ?>
</body>
  