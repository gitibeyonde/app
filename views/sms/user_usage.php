<?php
define('__SMSMROOT__', dirname(dirname(dirname(__FILE__))));
include_once(__ROOT__ .'/classes/core/Log.php');
include_once(__ROOT__ . '/classes/sms/SmsLog.php');
include_once(__ROOT__ . '/classes/sms/EmailLog.php');
include(__ROOT__.'/views/_header.php');


$_SESSION['log'] = new Log("info");
$user_id=$_SESSION['user_id'];


$EM = new EmailLog();
$SM = new SmsLog();

$submit = isset($_GET['submit']) ? $_GET['submit'] : null;
?>


<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/user_menu.php'); ?>
<br/>
<h4>Usage of this month</h4>

   <table class="table table-striped"> <tr>
    <td> Type
    </td>
    <td>Count
    </td>
    <td>Details
    </td>
   </tr>
   <tr>
    <td> Email
    </td>
    <td><?php echo $EM->getEmailLogCountForMonth($user_id); ?>
    </td>
    <td>
    <div class="col-lg-2 col-md-2">
        <form action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo USER_USAGE; ?>">
        <button type="submit" name="submit" value="email" style="background: transparent; border: 0;">Details</button>
        </form>
    </div>
    </td>
   </tr>
   
   <tr>
    <td> Sms Trigger
    </td>
    <td><?php echo $SM->getSMSLogTriggerCountForMonth($user_id); ?>
    </td>
    <td>
    <div class="col-lg-2 col-md-2">
        <form action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo USER_USAGE; ?>">
        <button type="submit" name="submit" value="trigger" style="background: transparent; border: 0;">Details</button>
        </form>
    </div>
    </td>
   </tr>
   
   
   <tr>
    <td> Sms Survey
    </td>
    <td><?php echo $SM->getSMSLogSurveyCountForMonth($user_id); ?>
    </td>
    <td>
    <div class="col-lg-2 col-md-2">
        <form action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo USER_USAGE; ?>">
        <button type="submit" name="submit" value="survey" style="background: transparent; border: 0;">Details</button>
        </form>
    </div>
    </td>
    
   </tr>
   </table>
   
   <hr/>
   <h4>Details</h4>
   <table class="table table-striped"> 
   <tr>
   <td>id</td><td>send to</td><td>message</td>
   </tr>
   <?php
   $details = array();
   if ($submit == "email"){
       $details = $EM->getEmailLogForMonth($user_id);
       foreach($details as $detail){
           echo "<tr><td>".$detail['id']."</td><td>".$detail['there_email']."</td><td>".substr($detail['email'], 0, 20)."</td></tr>";
       }
   }
   else if ($submit == "trigger"){
       $details = $SM->getSMSLogTriggerForMonth($user_id);
       foreach($details as $detail){
           echo "<tr><td>".$detail['id']."</td><td>".$detail['there_number']."</td><td>".substr($detail['sms'], 0, 20)."</td></tr>";
       }
   }
   else if ($submit == "survey"){
       $details = $SM->getSMSLogSurveyForMonth($user_id);
       foreach($details as $detail){
           echo "<tr><td>".$detail['id']."</td><td>".$detail['there_number']."</td><td>".substr($detail['sms'], 0, 20)."</td></tr>";
       }
   }
   
   ?>
   </table>
   
</div>
<?php 
include(__ROOT__.'/views/_footer.php');
?>
</body>
</html>