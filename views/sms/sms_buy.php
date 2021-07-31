<?php 
include(__ROOT__.'/views/_header.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_email = $_SESSION['user_email'];
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];
$user_phone = $_SESSION['user_phone'];
$type = $_POST['type'];
$phone=$_POST['phone'];

$tabella=null;
$amount=0;
$title="";
if ($type == 'mnth'){
    $tabella = __ROOT__ ."/views/sms/checkout/automatic_monthly.php";
    $amount = 299900;
    $title="Monthly Payment";
}
else if ($type== 'trmt'){
    $tabella = __ROOT__ ."/views/sms/checkout/automatic_trimonthly.php";
    $amount = 899900;
    $title="Three Months Payment";
}
else if ($type== 'annl'){
    $tabella = __ROOT__ ."/views/sms/checkout/automatic_annual.php";
    $amount = 3299900;
    $title="Annual Payment";
}

?>
<body>
    <main>
<div class="album py-5 bg-none">
    <div class="container">

    <h4>Phone number <?php echo $phone; ?> is reserved for this order</h4>
    <hr/> 
        
      <div class="row">
                      <br/>
                      <br/>
      </div>
      <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3><?php echo $title; ?></h3>
                </div>
                 <div class="card">
                      <div class="card-header" style="background:  #faebd7;">
                        <b>INR <?php echo $amount/100; ?> only</b>
                      </div>
                      <div class="card-body" style="background: #fff5ee;">
                      <p>The number will be available to you for 1 month. If it is not renewed before the month is over, then the number is released to the free pool.
                       The numbers from the free pool can be picked up by anyone willing to make use of them.</p>
                       <br/>
                       
                       <b>Please read terms and conditions for using the number. 
                           <a href="#" onClick="MyWindow=window.open('https://simonline.in/views/sms/legal/terms.html','Terms of use','width=600,height=900'); return false;">Terms of Use</a>
                      
                      <br/>
                      <br/>
                      <p>
                      <?php  require($tabella); ?>
                      </p>
                      <br/>
                      <a href="/index.php" class="btn btn-info btn-block">Back To Dashboard</a>
                      </div>
             </div>
        </div>
   </div>
        
 </div>
</div>    
 
 <?php include(__ROOT__.'/views/_footer.php'); ?>
</main>
</body>
