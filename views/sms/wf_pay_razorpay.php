<style>

.razorpay-payment-button {
    width: 80%;
    height: 60px;
    margin: 10%;
}

</style>
<?php
define ( '__ROOT__',  dirname (dirname (dirname ( __FILE__ ))));
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/sms/SmsMinify.php');
include_once(__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/libraries/vendor/razorpay/razorpay/Razorpay.php');
require_once(__ROOT__.'/classes/wf/data/WfPayment.php');
require_once(__ROOT__.'/classes/sms/SmsPayment.php');
// Create the Razorpay Order
use Razorpay\Api\Api;

$log = $_SESSION['log'] = new Log('debug');

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : (isset($_POST['user_id']) ? $_POST['user_id'] : null);
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : (isset($_POST['bot_id']) ? $_POST['bot_id'] : null);
$amount = isset($_GET['amnt']) ? $_GET['amnt'] : (isset($_POST['amnt']) ? $_POST['amnt'] : null);
$number = isset($_GET['t']) ? $_GET['t'] : (isset($_POST['t']) ? $_POST['t'] : null);

if ($number == -1 || $amount == -1){
    //OTP not set
    echo '<button style="color: red;border: 0px">**Payment processor is disabled on non OTP catalogs.</button>';
    return;
}

if ($user_id == null || $bot_id == null || $amount == null || $number == null){
    echo "Bad Transaction";
    die;
}

if ($amount > 500000){
    //user has done a transaction with this order id, who the details here
    echo "<h4>Transaction of more than Rs.5000 are not allowed. </h4>";
    echo "<h5>Contact the Merchant !</h5>";
    die;
}


$order_id =  $number."-".$bot_id;


$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);

$Sp = new SmsContext($user_id, $bot_id);
$lt = $Sp->getLastTransaction($order_id);

error_log(print_r($lt, true));

if ($lt != null){
    //user has done a transaction with this order id, who the details here
    echo "<h4>A Transcation on this Delta app with Id = ".$order_id." is still to be serviced. </h4>";
    echo "<h5>No payment allowed till Merchant services that transaction first !</h5>";
    die;
}

$Pp = new SmsPayment();
$Luser = $Pp->getUserData($user_id);
$merchant_name = $Luser['user_name'];
$merchant_phone = $Luser['user_phone'];
$merchant_email = $Luser['user_email'];

$Lpp = $Pp->getPPCreds($user_id, $Pp::RAZORPAY);
$cred=json_decode($Lpp['cred'], true);

if(!isset($cred)){
    //razorpay account not set
    echo '<button type="button" class="btn btn-warning">Payment processor not set</button>';
    return;
}
error_log("Cred =".print_r($cred, true).isset($cred['key']));

$amount = $amount * 100;

$api = new Api($cred['key'], $cred['secret']);

$displayCurrency='INR';
$orderData1 = [
        'receipt'         => $order_id,
        'amount'          => $amount, // 2000 rupees in paise
        'currency'        => "INR",
        'payment_capture' => 1 // auto capture
];
$razorpayOrder1 = $api->order->create($orderData1);
$razorpayOrderId1 = $razorpayOrder1['id'];
$_SESSION['razorpay_order_id1'] = $razorpayOrderId1;
$data = [
        "key"               => RZRID,
        "amount"            => $amount,
        "name"              => $Lpp['name'],
        "description"       => $Lpp['description'],
        "image"             => "https://www.ibeyonde.com/img/ico192.png",
        "prefill"           => [
                "name"              => $merchant_name,
                "email"             => $merchant_email,
                "contact"           => $merchant_phone,
        ],
        "notes"             => [
                "address"           => $Lpp['address'],
                "merchant_order_id" => $order_id,
        ],
        "theme"             => [
                "color"             => "#F37254"
        ],
        "order_id"          => $razorpayOrderId1,
];
$json = json_encode($data);

header("Set-Cookie", "HttpOnly;Secure;SameSite=Strict");
?>
<body>
 <form action="/views/sms/sms_verify.php" method="POST" style="width: 100%">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $data['key']?>"
    data-amount="<?php echo $data['amount']?>"
    data-currency="INR"
    data-name="<?php echo $data['name']?>"
    data-image="<?php echo $data['image']?>"
    data-description="<?php echo $data['description']?>"
    data-theme.color="<?php echo $data['theme']['color']?>"
    data-prefill.name="<?php echo $data['prefill']['name']?>"
    data-prefill.email="<?php echo $data['prefill']['email']?>"
    data-prefill.contact="<?php echo $data['prefill']['contact']?>"
    data-notes.shopping_order_id="<?php echo $order_id; ?>"
    data-order_id="<?php echo $data['order_id']?>"
  >
  </script>
  <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
  <input type=hidden name=user_id value="<?php echo $user_id; ?>">
  <input type=hidden name=number value="<?php echo $number; ?>">
  <input type=hidden name=amount value="<?php echo $amount; ?>">
  <input type=hidden name=order_id value="<?php echo $order_id; ?>">
 </form>
    
</body>
