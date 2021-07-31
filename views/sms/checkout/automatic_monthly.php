<!--  The entire list of Checkout fields is available at
 https://docs.razorpay.com/docs/checkout-form#checkout-fields -->
<?php 
require_once (__ROOT__ . '/libraries/vendor/razorpay/razorpay/Razorpay.php');
require_once(__ROOT__.'/classes/GsmOrder.php');
// Create the Razorpay Order
use Razorpay\Api\Api;

$api = new Api(RZRID, RZRSEC);

$displayCurrency='INR';

error_log("Automatic Monthly loaded");

$amount=100;

$gsm_order = new GsmOrder();
$order_id = $gsm_order->addOrder($user_id, 'annl', $phone, $amount, 'init');

$orderData1 = [
        'receipt'         => $order_id,
        'amount'          => $amount, // 2000 rupees in paise
        'currency'        => $displayCurrency,
        'payment_capture' => 1 // auto capture
];

$razorpayOrder1 = $api->order->create($orderData1);

$razorpayOrderId1 = $razorpayOrder1['id'];

$_SESSION['razorpay_order_id1'] = $razorpayOrderId1;

$data = [
        "key"               => RZRID,
        "amount"            => $amount,
        "name"              => "Ibeyonde",
        "description"       => "One Month Payment",
        "image"             => "https://www.ibeyonde.com/img/ico192.png",
        "prefill"           => [
                "name"              => $user_name,
                "email"             => $user_email,
                "contact"           => $user_phone,
        ],
        "notes"             => [
                "address"           => "T-Hub, Gachibowli, Hyderabad",
                "merchant_order_id" => "12312321",
        ],
        "theme"             => [
                "color"             => "#F37254"
        ],
        "order_id"          => $razorpayOrderId1,
];

$json = json_encode($data);
$gsm_order->updateVenderOrderId($user_id, $order_id, $razorpayOrderId1);
?>

<form action="/views/sms/sms_verify.php" method="POST">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $data['key']?>"
    data-amount="<?php echo $data['amount']?>"
    data-currency="INR"
    data-name="<?php echo $data['name']?>"
    data-image="<?php echo $data['image']?>"
    data-description="<?php echo $data['description']?>"
    data-prefill.name="<?php echo $data['prefill']['name']?>"
    data-prefill.email="<?php echo $data['prefill']['email']?>"
    data-prefill.contact="<?php echo $data['prefill']['contact']?>"
    data-notes.shopping_order_id="<?php echo $order_id; ?>"
    data-order_id="<?php echo $data['order_id']?>"
    <?php if ($displayCurrency !== 'INR') { ?> data-display_amount="<?php echo $data['display_amount']?>" <?php } ?>
    <?php if ($displayCurrency !== 'INR') { ?> data-display_currency="<?php echo $data['display_currency']?>" <?php } ?>
  >
  </script>
  <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
  <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
  <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
  <input type="hidden" name="phone" value="<?php echo $phone; ?>">
  <input type="hidden" name="type" value="mnth">
  <input type="hidden" name="amount" value="1.00">
</form>
