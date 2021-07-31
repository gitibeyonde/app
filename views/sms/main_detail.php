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
require_once (__ROOT__ . '/classes/sms/SmsLog.php');
require_once (__ROOT__ . '/classes/wf/SmsWfProcessor.php');
include(__ROOT__.'/views/_header.php');

$there_number = $_GET['there_number'];
$my_number = $_GET['my_number'];
$user_id = $_SESSION['user_id'];

if (isset($_GET['submit']) && $_GET['submit'] == "send_sms"){
    $testI = new SmsAgent();
    $sms=$_GET['sms'];
    $testI->simulateSend($user_id, $my_number, $there_number, $sms);
}

?>
<body>
<div class="container" style="padding-top: 120px;">

<div class="row">

    <div class="col-lg-2 col-mg-2 col-sm-12 col-12">
        <h6>Send test message</h6>
        <hr/>
           
          <form action="/index.php" method="get">
           <input type=hidden name=view value=<?php echo SMS_DETAIL; ?>>
              <label for="name"><h8>Phone:</h8><h7> <?php echo $there_number; ?></h7> </label>
              <br/>
              <label for="desc"><h8>SMS</h8> </label>
              <textarea class="form-control" rows="5" name="sms" onkeyup="countChar(this)" placeholder="Enter Sms Message" required></textarea>
             <input type=hidden name=my_number value=<?php echo $my_number; ?>>
             <input type=hidden name=there_number value=<?php echo $there_number; ?>>
             <br/>
        	<button type="submit" name="submit" value="send_sms" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>

    <div class="col-lg-7 col-mg-7 col-sm-12 col-12">
        <table class="table table-fixed"> 
           <thead>
            <tr>
            <td>
               <form class="form-inline" action="/index.php"  method="get">
        		     <input type=hidden name=view value=<?php echo MAIN_VIEW; ?>>
                 <button name=submit value=submit class="btn btn-info"><h7><em class=selthin> <i class='fas fa-2x fa-backward'></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo $there_number; ?> </em></h7></button>
               </form>
            </td>
            </tr>
            </thead>
         <tbody style="border: none;background-color: var(--tb3);">
           <?php 
                 $smslog = new SmsLog();
                 $logs = $smslog->getChatLogForMyNumberThereNumber($my_number, $there_number);
                 foreach ($logs as $log) {
           ?>
            <tr>
            <td class="break">
            <form class="form-inline" action="/index.php"  method="get">
                <input type=hidden name=view value=<?php echo SMS_DETAIL; ?>>
                <input type=hidden name=my_number value=<?php echo $my_number; ?>>
                <input type=hidden name=there_number value=<?php echo $there_number; ?>>
            <?php 
                if ($log['direction'] == 1) {
                    echo "<h8>".substr($log['changedOn'], 5, 11)."</h8>:"."<h7>".$log['sms']."</h7>";
                }
                else {
                    echo "<h8>".substr($log['changedOn'], 5, 11)."</h8>:"."<h5>".$log['sms']."</h5>";
                }
            ?>
            </td>
            </tr>
         <?php }  ?>
          </tbody>
         </table>   
    </div>
     
    <div class="col-lg-3 col-mg-3 col-sm-12 col-12">
        <h2>Help</h2>
        <hr/>
       	<h5> Any message sent from the emulator will be processed on the server. The message will be pretended to be sent from <?php echo $there_number; ?></h5>
        <br/>
        <h5> No real SMS via GSM network will be sent out </h5>
    </div>  
 </div>
   
</div>
<?php 
include(__ROOT__.'/views/_footer.php');
?>