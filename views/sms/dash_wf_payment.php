<?php
include(__ROOT__.'/views/_header.php');
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/device/GsmDevice.php');
require_once (__ROOT__ . '/libraries/vendor/razorpay/razorpay/Razorpay.php');
require_once(__ROOT__.'/classes/wf/data/WfPayment.php');
require_once(__ROOT__.'/classes/sms/SmsPayment.php');
// Create the Razorpay Order
use Razorpay\Api\Api;

$log = $_SESSION['log'] = new Log('debug');

$user_id = $_SESSION['user_id'];

$submit = isset($_GET['submit']) ? $_GET['submit'] : (isset($_POST['submit']) ? $_POST['submit'] : null);
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : null;

$Pp = new SmsPayment();

$Lpp = $Pp->getPPCreds($user_id, $Pp::RAZORPAY);
$cred=json_decode($Lpp['cred'], true);
error_log("Cred =".print_r($cred, true).isset($cred['key']));
if (!isset($cred['key']) && $submit == "addcred"){
    $name = $_GET['name'];
    $description = $_GET['description'];
    $address = $_GET['address'];
    $key = $_GET['key'];
    $secret = $_GET['secret'];
    $cred = array();
    $cred['key'] = $key;
    $cred['secret'] = $secret;
    $Pp->savePPCreds($user_id, $name, $address, $description, $Pp::RAZORPAY, json_encode($cred));
}
else if ($submit == "delete"){
    $pp = $_GET['pp'];
    $Pp->deletePPCreds($user_id, $pp);
}
else if ($submit == "updatetaxes"){
    $tax1_name = $_GET['tax1_name'];
    $tax1_percent = $_GET['tax1_percent'];
    $tax2_name = $_GET['tax2_name'];
    $tax2_percent = $_GET['tax2_percent'];
    $tax3_name = $_GET['tax3_name'];
    $tax3_percent = $_GET['tax3_percent'];
    $Pp->saveTaxation($user_id, $tax1_name, $tax1_percent, $tax2_name, $tax2_percent, $tax3_name, $tax3_percent);
}

if (isset($cred['key'])){
    $api = new Api($cred['key'], $cred['secret']);
    $displayCurrency='INR';
    $order_id =  mt_rand ( 10000000000, 999999999999 );
    $orderData1 = [
            'receipt'         => $order_id,
            'amount'          => "100", // 2000 rupees in paise
            'currency'        => "INR",
            'payment_capture' => 1 // auto capture
    ];
    $razorpayOrder1 = $api->order->create($orderData1);
    $razorpayOrderId1 = $razorpayOrder1['id'];
    $_SESSION['razorpay_order_id1'] = $razorpayOrderId1;
    $data = [
            "key"               => RZRID,
            "amount"            => 1,
            "name"              => $Lpp['name'],
            "description"       => $Lpp['description'],
            "image"             => "https://www.ibeyonde.com/img/ico192.png",
            "prefill"           => [
                    "name"              => $Lpp['name'],
                    "email"             => $_SESSION['user_email'],
                    "contact"           => $_SESSION['user_phone'],
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
}
$wf= array();
$wf['name']="For All";
$wf['category']="All";

$Ltaxes = $Pp->getTaxation($user_id);
$tax1_name = $Ltaxes['tax1_name'];
$tax2_name = $Ltaxes['tax2_name'];
$tax3_name = $Ltaxes['tax3_name'];
$tax1_percent = $Ltaxes['tax1_percent'];
$tax2_percent = $Ltaxes['tax2_percent'];
$tax3_percent = $Ltaxes['tax3_percent'];

error_log("Taxes=".print_r($Ltaxes, true));
?>

<body>
<div class="container-fluid top">  
   <?php include(__ROOT__.'/views/sms/dash_menu_top.php'); ?>

    <div class="row">
        <div class="col-lg-2 col-md-2">
            <h4><?php echo $wf['name']; ?></h4>
            <h5><?php echo $wf['category']; ?></h5>
        </div><!-- End first Column -->
        
        <div class="col-lg-7 col-md-7">
            <h3>Setup Payment Processor</h3>
            <hr/>
            <table class="table-stripped">
                <tr><td>
                
                <?php if (isset($cred['key'])){ ?>
                  <h4> Test Razor pay (Rs. 1)</h4>
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
                 </form>
                </td><td>
                   <form action="/index.php"  method="get">
                    <input type=hidden name=pp value="<?php echo $Lp['pp']; ?>">
                    <input type=hidden name=view value="<?php echo PAYMENT_SETUP; ?>">
                    <button type="submit" name="submit" value="delete" class="btn btn-sim2">Delete</button>
                   </form>
                <?php } else { ?>
                  <h4> Input credentials for enabling Razor pay</h4>
                  <hr/>
                   <form action="/index.php"  method="get">
                    <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                    <input type=hidden name=view value="<?php echo PAYMENT_SETUP; ?>">
                    <label>Merchant Name</label>
                    <input class="form-control" type="text" name="name" size="60" value="">
                    <label>Description</label>
                    <input class="form-control" type="text" name="description" size="120" value="">
                    <label>Address</label>
                    <input class="form-control" type="text" name="address" size="140" value="">
                    <label>RazorPay Key</label>
                    <input class="form-control" type="text" name="key" value="<?php echo isset($cred['key']) ? $cred['key'] : ""; ?>">
                    <label>RazorPay Secret</label>
                    <input class="form-control" type="text" name="secret" value="<?php echo isset($cred['secret']) ? $cred['secret'] : ""; ?>">
                    <button class="btn btn-sim1" type="submit" name="submit" value="addcred">Add</button>
                   </form>
                <?php } ?>
                </td></tr>
            </table>
            <hr/>
            <br/>
            <h3>Setup Taxes</h3>
            <form action="/index.php"  method="get">
                <input type=hidden name=view value="<?php echo PAYMENT_SETUP; ?>">
                <h6>Add tax item 1, if you want to tax the user.</h6>
                <label>Tax 1, Name:</label>
                    <input type=text name=tax1_name value="<?php echo $tax1_name; ?>">
                <label>Tax 1, Percent:</label>
                    <input type=text name=tax1_percent value="<?php echo bcmul($tax1_percent,100); ?>">
               <h6>Add tax item 2, if there is none leave it blank.</h6>
                <label>Tax 2, Name:</label>
                    <input type=text name=tax2_name value="<?php echo $tax2_name; ?>">
                <label>Tax 2, Percent:</label>
                    <input type=text name=tax2_percent value="<?php echo bcmul($tax2_percent,100); ?>">
               <h6>Add tax item 3, if there is none leave it blank.</h6>
                <label>Tax 3, Name:</label>
                    <input type=text name=tax3_name value="<?php echo $tax3_name; ?>">
                <label>Tax 3, Percent:</label>
                    <input type=text name=tax3_percent value="<?php echo bcmul($tax3_percent,100); ?>">
                    <button type="submit" name="submit" value="updatetaxes" class="btn btn-sim1">Update</button>
            </form>
        </div>
     </div> 
</div>

</body>
<?php 
include(__ROOT__.'/views/_footer.php');
?>