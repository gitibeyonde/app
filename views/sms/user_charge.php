<?php
include(__ROOT__.'/views/_header.php');
require_once(__ROOT__.'/config/config.php');
include_once(__ROOT__ .'/classes/core/Log.php');
include_once(__ROOT__ .'/classes/core/Mysql.php');
require_once (__ROOT__ . '/libraries/vendor/razorpay/Razorpay.php');
require_once(__ROOT__.'/classes/wf/data/WfPayment.php');

// Create the Razorpay Order
use Razorpay\Api\Api;

$api = new Api(RZRID, RZRSEC);

$displayCurrency='INR';

$_SESSION['log'] = new Log("info");
$user_id=$_SESSION['user_id'];

$submit = isset($_POST['submit']) ? $_POST['submit'] : null;

$gsm_order = new WfPayment();

if ($submit == "charge"){
    $amount = $_POST['amount']*100;

    $order_id = $gsm_order->addOrder($user_id, 'recharge', $_SESSION['user_phone'], $_POST['amount'], 'init');
    
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
            "description"       => "Recharge of ".$amount,
            "image"             => "https://www.ibeyonde.com/img/ico192.png",
            "prefill"           => [
                    "name"              => "Ibeyonde Cloud Service Pvt Ltd",
                    "email"             => "info@ibeyonde.com",
                    "contact"           => "+919701199011",
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

}

$this_month_charge = $gsm_order->getThisMonthDeposit($user_id);

error_log("This monht charge=".$this_month_charge);

?>

<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/user_menu.php'); ?>
<br/>
<h4>Recharge</h4>
 <p>We provide transaction SMS via console and API at the rate of 50 paise/SMS. Promotional SMSes are provided at 25 paise/SMS.
Transactional and promotional email will cost 25 paise/email. The model is pay as you go with minimum deposit of Rs 50.
</p>
<p>This month total recharge: INR <?php echo $this_month_charge; ?> </p>

<?php if ($submit == "charge"){ ?>


<form action="/views/sms/user_charge_verify.php" method="POST">
  <label>INR. <?php echo $_POST['amount']; ?>.00 </label>
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
  >
  </script>
  <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
  <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
  <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
  <input type="hidden" name="phone" value="<?php echo $_SESSION['user_phone']; ?>">
  <input type="hidden" name="amount" value="<?php echo $amount; ?>">
</form>

<?php } else {?>

<div class="row" style="background: ghostwhite;padding: 20px;">
     
    <div class="col-lg-2 col-md-2">
    	<!-- <form class="form-inline" action="/index.php"  method="post">
        <input type=hidden name=view value="<?php echo USER_CHARGE; ?>">
        <input type=hidden name=amount value="1">
        <button type="submit" name="submit" value="charge" class="btn btn-success">1</button>
        </form> -->
    </div>
    
    <div class="col-lg-2 col-md-2">
    	<form class="form-inline" action="/index.php"  method="post">
        <input type=hidden name=view value="<?php echo USER_CHARGE; ?>">
        <input type=hidden name=amount value="50">
        <button type="submit" name="submit" value="charge" class="btn btn-success">50</button>
        </form>
    </div>
    
    <div class="col-lg-2 col-md-2">
    	<form class="form-inline" action="/index.php"  method="post">
        <input type=hidden name=view value="<?php echo USER_CHARGE; ?>">
        <input type=hidden name=amount value="100">
        <button type="submit" name="submit" value="charge" class="btn btn-success">100</button>
        </form>
    </div>
    
    <div class="col-lg-2 col-md-2">
    	<form class="form-inline" action="/index.php"  method="post">
        <input type=hidden name=view value="<?php echo USER_CHARGE; ?>">
        <input type=hidden name=amount value="500">
        <button type="submit" name="submit" value="charge" class="btn btn-success">500</button>
        </form>
    </div>
    
    <div class="col-lg-2 col-md-2">
    	<form class="form-inline" action="/index.php"  method="post">
        <input type=hidden name=view value="<?php echo USER_CHARGE; ?>">
        <input type=hidden name=amount value="1000">
        <button type="submit" name="submit" value="charge" class="btn btn-success">1000</button>
        </form>
    </div>
    
    <div class="col-lg-2 col-md-2">
    	<form class="form-inline" action="/index.php"  method="post">
        <input type=hidden name=view value="<?php echo USER_CHARGE; ?>">
        <input type=hidden name=amount value="2000">
        <button type="submit" name="submit" value="charge" class="btn btn-success">2000</button>
        </form>
    </div>
    
    <div class="col-lg-2 col-md-2">
    </div>
</div>
<?php } ?>


</div>
<?php 
include(__ROOT__.'/views/_footer.php');
?>
</body>
</html>