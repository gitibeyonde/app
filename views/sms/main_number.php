
<?php
include(__ROOT__.'/views/_header.php');
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
require_once (__ROOT__ . '/classes/device/GsmDevice.php');


$user_id = $_SESSION['user_id'];

error_log("User id=".$user_id);
$gsmdev = new GsmDevice();
if (isset($_GET['submit']) && $_GET['submit'] == "add_virtual"){
    $gsmdev->addVirtualNumber($user_id);
}

$numbers = $gsmdev->getUnusedGsmDevices();
$my_numbers = $gsmdev->getGsmDevice($user_id);

?>
<body>
<div class="container"  style="padding-top: 120px;">

<form action="/index.php"  method="get" style="float: left;">
<input type=hidden name=view value="main_view">
<input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
<button class="btn btn-sim1" type="submit" name="submit" value="db"><i class="fas fa-arrow-left fa-2x"  style="color: orange"></i></button>
</form>


<h4>My Phone Numbers </h4>
<hr/>
        <br/>
          <div class="row">
            <table class="table table-striped table-hover">
                <tr>
                <td><h5>Number</h5>
                </td>
                <td><h5>Expiry</h5>
                </td>
                </tr>
                
             <?php if (sizeof($my_numbers) == 0 ) {?>
                 <tr>
                 <td colspan=3>
                    <h4> None</h4>
                 </td>
                 </tr>
             <?php } 
             else {
                 foreach ($my_numbers as $num) {
             ?>
                <tr>
                <td><h6><?php echo $num['my_number']; ?></h6>
                </td>
                <td><h6><?php echo $num['end_date']; ?></h6>
                </td>
                </tr>
             <?php }} ?>
             </table>
          </div>
          
          
          
        <hr/>
        <br/>
          <?php if (sizeof($my_numbers) == 0 ) {?>
        <h4>Claim your virtual number </h4>
        <br/>
            <form class="form-inline" action="/index.php?view=<?php echo ADD_NUMBER; ?>"  method="get">
                    <button name="submit" type="submit" value="add_virtual"  class="btn btn-sim3"><h6>Add Virtual</h6></button>
             </form>
        <br/>
        <hr/>
        <br/>
        <?php } ?>
        <h4>Hosted Numbers Available </h4>
        <br/>
          <div class="row">
            <table class="table table-striped table-hover">
                <tr>
                <td><h5>Number</h5>
                </td>
                <td><h5>3-Mnth</h5>
                </td>
                <td><h5>1-Year</h5>
                </td>
                </tr>
             <?php foreach ($numbers as $num) { ?>
                <tr>
                <td><h6><?php echo $num['my_number']; ?></h6>
                </td>
                <td>
                    <form class="form-inline" action="/index.php?view=<?php echo SMS_PAY; ?>"  method="post">
                    <input type=hidden name=type value='trmt'>
                    <input type=hidden name=phone value=<?php echo $num['my_number']; ?>>
                    <button name="submit" type="submit" value="subscribe"  class="btn btn-sim3"><h6>INR 8999</h6></button>
                    </form>
                </td>
                <td>
                    <form class="form-inline" action="/index.php?view=<?php echo SMS_PAY; ?>"  method="post">
                    <input type=hidden name=type value='annl'>
                    <input type=hidden name=phone value=<?php echo $num['my_number']; ?>>
                    <button name="submit" type="submit" value="subscribe"  class="btn btn-sim3"><h6>INR 32999</h6></button>
                    </form>
                </td>
                </tr>
             <?php }?>
             </table>
          </div>
    
</div> 
          
          
<?php 
include(__ROOT__.'/views/_footer.php');
?>