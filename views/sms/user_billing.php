<?php
define('__SMSMROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/libraries/aws.phar');
include(__ROOT__.'/views/_header.php');
include_once(__ROOT__ .'/classes/core/Log.php');
include_once(__ROOT__ .'/classes/core/Mysql.php');


$_SESSION['log'] = new Log("info");
$user_id=$_SESSION['user_id'];

include_once(__ROOT__ . '/classes/sms/SmsLog.php');
include_once(__ROOT__ . '/classes/sms/EmailLog.php');

$EM = new EmailLog();
$SM = new SmsLog();

$email_count = $EM->getEmailLogCountForMonth($user_id);
$trigger_count = $SM->getSMSLogTriggerCountForMonth($user_id);
$survey_count = $SM->getSMSLogSurveyCountForMonth($user_id); 
?>

<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/user_menu.php'); ?>
<br/>
<h4>Month to date</h4>

   <table class="table table-striped"> <tr>
    <td>Type
    </td>
    <td>Count
    </td>
    <td>Price
    </td>
    <td>Cost
    </td>
   </tr>
   <tr>
    <td> Email
    </td>
    <td><?php echo $email_count;  ?>
    </td>
    <td> Rs 0.25
    </td>
    <td>
    <?php echo "Rs.".$email_count * 0.25; ?>
    </td>
   </tr>
   
   <tr>
    <td> Sms Trigger
    </td>
    <td><?php echo  $trigger_count; ?>
    </td>
    <td> Rs 0.50
    </td>
    <td> 
    <?php echo "Rs.".$trigger_count * 0.50; ?>
    </td>
   </tr>
   
   
   <tr>
    <td> Sms Survey
    </td>
    <td><?php echo $survey_count; ?>
    </td>
    <td> Rs 0.25
    </td>
    <td>
     <?php echo "Rs.".$survey_count * 0.25; ?>
    </td>
    
   </tr>
   
   
   <tr>
    <td> 
    </td>
    <td> 
    </td>
    <td>Total
    </td>
    <td>
     <h5><?php echo "Rs.".($survey_count * 0.25 + $trigger_count * 0.50 + $email_count * 0.25); ?></h5>
    </td>
    
   </tr>
   </table>

</div>
<?php 
include(__ROOT__.'/views/_footer.php');
?>
</body>
</html>