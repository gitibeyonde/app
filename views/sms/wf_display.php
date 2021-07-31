<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
<link rel="stylesheet" href="/vendors/bootstrap5/css/bootstrap.min.css">
<script src="/vendors/jquery/jquery-3.2.1.min.js"></script>
<script src="/vendors/bootstrap/bootstrap.bundle.min.js"></script>
<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__.'/classes/sms/SmsPayment.php');
include_once(__ROOT__ .'/classes/core/Log.php');
include_once(__ROOT__ .'/classes/wf/actions/Action.php');
include_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
include_once (__ROOT__ . '/classes/wf/utils/WfUtils.php');


$log = $_SESSION['log'] = new Log("info");

$m=urldecode(base64_decode($_GET['m']));
list($message, $bot_id, $user_id, $css) = explode("^^", $m);

error_log("$bot_id, $user_id, $css");

$Act = new Action($user_id, $bot_id, "911111111111");

$message = SmsWfUtils::join($Act->substitue_pattern($message));

echo WfUtils::_css($css, 8);
?>

<body>
<div class="container top-container">
    <div class="chat-display-card">
       <div class="chat-input-card">
         <div class="main-text">
  				<?php echo $message; ?>
  		</div>
  	  </div>
  	</div>
</body>
