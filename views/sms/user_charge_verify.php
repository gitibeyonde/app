<?php 
define ( '__ROOT__', dirname ( dirname (dirname ( __FILE__ ))));
include_once (__ROOT__ . '/libraries/vendor/razorpay/Razorpay.php');
include_once(__ROOT__.'/classes/wf/data/SmsContext.php');
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/config/config.php');
require_once(__ROOT__.'/classes/wf/data/WfPayment.php');
include(__ROOT__.'/views/_header.php');

$log = $_SESSION['log'] = new Log('debug');
// Create the Razorpay Order
use Razorpay\Api\Api;

error_log(print_r($_POST, true));
error_log(print_r($_GET, true));

$user_id = $_POST['user_id'];
$order_id=$_POST['order_id'];
$amount=$_POST['amount'];
$razorpay_order_id=$_POST['razorpay_order_id'];
$razorpay_payment_id=$_POST['razorpay_payment_id'];
$razorpay_signature=$_POST['razorpay_signature'];

$api = new Api(RZRID, RZRSEC);
$attributes  = array("razorpay_signature"  => $razorpay_signature,  "razorpay_payment_id"  => $razorpay_payment_id,  "razorpay_order_id" => $razorpay_order_id);
$order  = $api->utility->verifyPaymentSignature($attributes);

error_log(print_r($order, true));

$gsm_order = new WfPayment();
$order_id = $gsm_order->captureOrder($user_id, $order_id, $razorpay_signature);//$user_id, $order_id, $type, $phone, $vendor_payment_id){
?>

<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/user_menu.php'); ?>
	<br/>
    <h5>Your payment of Rs.<?php echo $amount/100; ?> is successful !</h5>
    
    <hr/> 
    <h6>Transaction Id: <?php echo $razorpay_order_id; ?></h6>
    <br/>
    <br>
</div>
<?php 
include(__ROOT__.'/views/_footer.php');
?>
</body>
</html>