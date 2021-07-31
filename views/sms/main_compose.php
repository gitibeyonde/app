 <script>
  function countChar(val) {
    var len = val.value.length;
    if (len >= 170) {
      val.value = val.value.substring(0, 170);
    } else {
      $('#charNum').text(170 - len);
    }
  };
</script> 
<?php
define('__SMSMROOT__', dirname(dirname(dirname(__FILE__))));
$user_id = $_SESSION['user_id'];
include(__ROOT__.'/views/_header.php');

require_once (__ROOT__ . '/classes/sms/SmsLog.php');

//$sel_num = $_SESSION['sel_num'];
?>
<body>
<div class="container" style="padding-top: 120px;">

<div class="row">

    <div class="col-lg-2 col-mg-2 col-sm-12 col-12">
    <h6>Numbers</h6>
    <hr/>
    <h7 class=selthin><?php echo $sel_num; ?></h7>
    </div>

    <div class="col-lg-7 col-mg-7 col-sm-12 col-12">
       
          <form action="/index.php" method="get">
           <input type=hidden name=view value=<?php echo MAIN_VIEW; ?>>
           <input type=hidden name=sel_num value=<?php echo $sel_num; ?>>
            <div class="form-group row flex-v-center">
            <div class="col-xs-2 col-sm-2">
              <label for="name">To:</label>
            </div>
            <div class="col-xs-12">
              <input type="text" class="form-control" id="from_number" placeholder="911111111111" name="from_number" value="911111111111" required>
              <div class="valid-feedback">Valid.</div>
              <div class="invalid-feedback">Please fill out this field.</div>
             </div>
           </div>
            
           <div class="form-group row flex-v-center">
            <div class="col-xs-2 col-sm-2">
              <label for="desc">SMS </label>
            </div>
             <div class="col-xs-12">
                <textarea class="form-control" rows="5" cols=50 name="sms" onkeyup="countChar(this)" placeholder="Enter Sms Message" required></textarea>
               <div id="charNum"></div>
              <div class="valid-feedback">Valid.</div>
              <div class="invalid-feedback">Please fill out this field.</div>
             </div>
             </div>
             
            <div class="form-group row flex-v-center">
            <div class="col-xs-4 col-sm-4">
                <button type="submit" name="submit" value="send_sms" class="btn btn-primary"><i class="fas fa-paper-plane fa-2x"></i></button>
             </div>
             </div>
          </form>
        
    </div>
     
    <div class="col-lg-3 col-mg-3 col-sm-12 col-12">
        <h2>Help</h2>
        <hr/>
        <?php  if (substr($sel_num, 0, 1) == "2" ) { ?>
        <h5> Out here you can send a message to your virtual number. 
        The message will be appear to be sent from the number that you provide.
        The virtual message will never be sent out by GSM device or attached device.
        This is ONLY to test backend.
        </h5>
        <?php } else { ?>
        <h5> Out here you can send a message to your attached number. 
        The message will appear to be sent from the number that you provide.
        The virtual message will never be sent out by GSM device or attached device.
        This is ONLY to test backend.
         </h5>
         <br/>
        <h5> You can send a real message from any real phone number to this number here.
        This is purely a emulator to test backend.
         </h5>
        <?php } ?>
    </div>  
 </div>
   
</div> 
<?php 
include(__ROOT__.'/views/_footer.php');
?>