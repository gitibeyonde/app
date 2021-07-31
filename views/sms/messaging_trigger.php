<?php
include(__ROOT__.'/views/_header.php');

require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/sms/SmsSend.php');
require_once (__ROOT__ . '/classes/sms/SmsMessage.php');
require_once (__ROOT__ . '/classes/EmailUtils.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/data/WfPayment.php');


$log = $_SESSION['log'] = new Log ("info");
$user_id = $_SESSION['user_id'];

$SU = new SmsUtils();
$smsmessage = new SmsMessage();

$submit = $_POST['submit'];

error_log("Message Post is = " . $submit);
$WFP = new WfPayment();
$balance = $WFP->getThisMonthBalance($user_id);
$email_message_id=null;
$sms_message_id=null;
if (isset($submit)){
    $submit = $_POST['submit'];
    error_log("Action is " . $submit);
    $email_message_id = $_POST ['email_message_id'];
    $sms_message_id = $_POST ['sms_message_id'];
    if ($submit == "trigger") {
        error_log ( "messaging id is=" . $email_message_id.$sms_message_id );
    } else if ($submit == "sms_execute") {
        if ($balance > 0){
            $template = $_POST ['template'];
            $Lperson=array();
            $Lperson['number']=$_POST['phone'];
            $fields = $SU->templateInputFields($template);
            foreach ($fields as $field){
                error_log($field."=".$_POST[$field]);
                if ($field != "otp" && $field != "4otp" && $field != "6otp"){
                    $Lperson[$field]=$_POST[$field];
                }
            }
            $Lperson["otp"]=rand(100000, 999999);
            SmsSend::sendTemplateToPerson($user_id, "trigger", $sms_message_id, $Lperson);
        }
        else {
            $_SESSION['message'] = "No balance to exceute trigger";
        }
    } else if ($submit == "email_execute") {
        if ($balance > 0){
            $template = $_POST ['template'];
            $Lperson=array();
            $Lperson['email']=$_POST['email'];
            $fields = $SU->templateInputFields($template);
            foreach ($fields as $field){
                error_log($field."=".$_POST[$field]);
                if ($field != "otp" && $field != "4otp" && $field != "6otp"){
                    $Lperson[$field]=$_POST[$field];
                }
            }
            $Lperson["otp"]=rand(100000, 999999);
            $eutils = new EmailUtils();
            $eutils->sendTemplateToPerson($user_id, "trigger", $email_message_id, $Lperson);
        }
        else {
            $_SESSION['message'] = "No balance to exceute trigger";
        }
    }
}
$messages = $smsmessage->getMessage($user_id);
$emailmessages = $smsmessage->getEmailMessage($user_id);

?>
<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/messaging_menu.php'); ?>
            <br/>
            <h4>Trigger Message</h4>
            <p>Current Balance is Rs. <?php echo $balance; ?></p>
            <?php if ($balance <=0 ){?>
                 <form action="/index.php"  method="get">
                    <input type=hidden name=view value="<?php echo USER_CHARGE; ?>">
            		<label>To send the messages you need money in your account, </label>
                    <button type="submit" name="submit" value="add" style="background: transparent; border: 0;">Add Balance.</button>
                </form>
            <?php }?>
            
            <br/>
            <h5>SMS Trigger Template</h5>
          <table class="table table-striped">
            <?php
            if ($messages == null || count($messages) == 0) {
                echo "<tr><td colspan=5><font style='color: #3862c6;'>No message templates found, create one !</font></td></tr>";
            } else {
           ?> 
           <tr>
           <th>Id</th>
           <th>Name</th>
           <th>Template</th>
           <th>Response </th>
           <th>Run</th>
          </tr>
           <?php    foreach ($messages as $map) { ?>
            <tr width="100%">
            <td><?php echo $map['id']; ?></td>
            <td><?php echo $map['name']; ?></td>
            <td><?php echo $map['template']; ?></td>
            <td><?php echo $map['response']; ?><?php echo $map['id']; ?></td>
              <td width="20px">
                   <form  class="form-inline" action="/index.php?view=<?php echo SMS_TRIGGER; ?>"  method="post">
                    <input type=hidden name=sms_message_id value=<?php echo $map['id']; ?>>
                    <button name="submit" type="submit" value="trigger" class="btn btn-sim1"  data-toggle="tooltip" title="Check the final mapping">
                    Open</button>
               </form>
              </td>
            </tr>
            <?php if ($submit == "trigger"  && $sms_message_id == $map['id']){ ?>
            <tr>
               <td colspan=5>
               <form  class="form-inline" action="/index.php?view=<?php echo SMS_TRIGGER; ?>"  method="post">
                <table>
                 <tr>
               <?php 
               $fields = $SU->templateInputFields($map['template']);
               foreach ($fields as $field){
                   if ($field != "otp" && $field != "4otp" && $field != "6otp"){
                    echo "<td>".$field."</td>"; ?>
                   <td> 
                   <div class="form-group">
                       <input type=text name=<?php echo $field;?> value="" placeholder="Enter <?php echo $field;?>" required>
                       <div class="valid-feedback">Valid.</div>
                       <div class="invalid-feedback">Please fill out this field. </div>
                    </div>
                   </td>
                 <?php }} ?>
                  <td>phone</td>
                   <td> 
                   <div class="form-group">
                       <input type=text name=phone value="" placeholder="Enter phone number" required>
                       <div class="valid-feedback">Valid.</div>
                       <div class="invalid-feedback">Please fill out this field. </div>
                </div>
                   </td>
               <td>
               <input type="hidden" name="template" value="<?php echo $map['template']; ?>">
               <input type="hidden" name="sms_message_id" value="<?php echo $map['id']; ?>">
               <button name="submit" type="submit" value="sms_execute"  class="btn btn-sim1" data-toggle="tooltip" title="Go ahead run the check">Run</button>
               </td>
               </tr>
               </table>
              </form>
             <td>
           </tr>
             <?php } } } ?>
        </table>
        
         <br/>
            <h5>Email Trigger Template</h5>
          <table class="table table-striped">
            <?php
            if ($emailmessages == null || count($emailmessages) == 0) {
                echo "<tr><td colspan=5><font style='color: #3862c6;'>No message templates found, create one !</font></td></tr>";
            } else {
           ?> 
           <tr>
           <th>Id</th>
           <th>Name</th>
           <th>Template</th>
           <th>Response </th>
           <th>Run</th>
          </tr>
           <?php    foreach ($emailmessages as $map) { ?>
            <tr width="100%">
            <td><?php echo $map['id']; ?></td>
            <td><?php echo $map['name']; ?></td>
            <td><?php echo $map['template']; ?></td>
            <td><?php echo $map['response']; ?><?php echo $map['id']; ?></td>
              <td width="20px">
                   <form  class="form-inline" action="/index.php?view=<?php echo SMS_TRIGGER; ?>"  method="post">
                    <input type=hidden name=email_message_id value=<?php echo $map['id']; ?>>
                    <button name="submit" type="submit" value="trigger" class="btn btn-sim1"  data-toggle="tooltip" title="Check the final mapping">
                    Open</button>
               </form>
              </td>
            </tr>
            <?php if ($submit == "trigger"  && $email_message_id == $map['id']){ ?>
            <tr>
               <td colspan=5>
               <form  class="form-inline" action="/index.php?view=<?php echo SMS_TRIGGER; ?>"  method="post">
                <table>
                 <tr>
               <?php 
               $fields = $SU->templateInputFields($map['template']);
               foreach ($fields as $field){
                   if ($field != "otp" && $field != "4otp" && $field != "6otp"){
                    echo "<td>".$field."</td>"; ?>
                   <td> 
                   <div class="form-group">
                       <input type=text name=<?php echo $field;?> value="" placeholder="Enter <?php echo $field;?>" required>
                       <div class="valid-feedback">Valid.</div>
                       <div class="invalid-feedback">Please fill out this field. </div>
                    </div>
                   </td>
                 <?php }} ?>
                  <td>email</td>
                   <td> 
                   <div class="form-group">
                       <input type=text name=email value="" placeholder="Enter email" required>
                       <div class="valid-feedback">Valid.</div>
                       <div class="invalid-feedback">Please fill out this field. </div>
                </div>
                   </td>
               <td>
               <input type="hidden" name="template" value="<?php echo $map['template']; ?>">
               <input type="hidden" name="email_message_id" value="<?php echo $map['id']; ?>">
               <button name="submit" type="submit" value="email_execute"  class="btn btn-sim1" data-toggle="tooltip" title="Go ahead run the check">Run</button>
               </td>
               </tr>
               </table>
              </form>
             <td>
           </tr>
             <?php } } } ?>
        </table>
        
     

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="panel-group" id="accordion">
            <!-- First Panel -->
            <div class="panel panel-default">
                <div class="panel-heading">
                     <h5 class="panel-title"
                         data-toggle="collapse" 
                         data-target="#collapseOne">
                         <p style="text-decoration: underline;">API-Calls</p>
                     </h5>
                </div>
                <div id="collapseOne" class="panel-collapse collapse">
                    <div class="panel-body">
                       <h3>Make trigger calls, as follows::</h3>
                       <hr/>
                       <ul>
                       <li style="padding: 20px;"><h4>Email Trigger</h4> 
                       <h5>https://www.ibeyonde.com/api/email_send.php?host_key=[host-key]&email=[TO-EMail-Id]&template=[template-id]</h5>
                       </li>
                       
                       <li style="padding: 20px;"><h4>SMS Trigger</h4> 
                       <h5>https://www.ibeyonde.com/api/sms_send.php?host_key=[host-key]&phone=[Full-phone-number]&template=[template-id]</h5></h5>
                       </li>
                       
                       </ul>
                       <p>
                       You will get your hos-key in the user section. The phone number should be in international format e.g. +919701199011.
                       </p>
                    </div>
                </div>
            </div>
         </div>
    </div> <!-- column -->
</div> <!-- row -->   

 
</div>
         
</main>
<?php include(__ROOT__.'/views/_footer.php'); ?>
 </body>
  