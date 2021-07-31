<?php
require_once (__ROOT__ . '/classes/device/GsmDevice.php');
require_once (__ROOT__ . '/classes/sms/SmsLog.php');
require_once (__ROOT__ . '/classes/wf/SmsWfProcessor.php');

$user_id = $_SESSION['user_id'];
$gsmdev = new GsmDevice();
$my_numbers = $gsmdev->getGsmDevice($user_id);

include (__ROOT__.'/views/_header.php');

if (isset($_GET['submit']) && $_GET['submit'] == "send_sms") {
    $from=$_GET['from_number'];
    $sms=$_GET['sms'];
    $to=$_GET['sel_num'];;
    //$testI->simulateSend($user_id, $to, $from, $sms);
}

?>
<body>
<div class="container"  style="padding-top: 120px;">

<div class="row">

<div class="col-lg-2 col-mg-2 col-sm-12 col-12"> 
    <h4>Numbers</h4>
    <hr/>
       <table class="table table-striped">
        <tbody>
            <form class="form-inline" action="/index.php"  method="get">
            <input type=hidden name=view value=<?php echo WORKFLOW_LISTING; ?>>
            <?php foreach ($my_numbers as $num) {
                if ($sel_num == $num['my_number']){
                    $selected = "checked";
                }
                else {
                    $selected = "";
                }
              ?>
            <tr><td> 
                <input type="radio" name="sel_num" value="<?php echo $num['my_number']; ?>"  <?php echo $selected; ?>>
                <label><h8><?php echo $num['my_number']; ?></h8></label>
            </td></tr>
            <?php }  ?> 
       <tr><td> 
             <button type="submit" name="submit" value="sel_num" class="btn btn-sim1">Activate</button>
            </form>
       </td></tr>
        <tr><td>
            <form class="form-inline" action="/index.php"  method="get">
             <input type=hidden name=view value=<?php echo ADD_NUMBER; ?>>
             <button type=submit name="submit" value=submit class="btn btn-sim1">Add Number
            </button>
            </form>
        </td></tr>
        </tbody>
       </table>
       
      
</div>

<div class="col-lg-7 col-mg-7 col-sm-12 col-12">
        <table class="table table-fixed"> 
           <thead>
              <?php if (isset($sel_num)) { ?>
                    <tr><td>
                        <form class="form-inline" action="/index.php"  method="get">
                        <input type=hidden name=view value=<?php echo SMS_COMPOSE; ?>>
                        <input type=hidden name=my_number value=<?php echo $num['my_number']; ?>>
                        <button type=submit value=submit class="btn btn-info">
                            <h8><em class=selthin><i class='fas fa-1x fa-edit'></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sel_num; ?></em></h8>
                        </button>
                        </form>
                    </td>
                    <td>
                     <?php if (isset($_GET["submit"]) && $_GET["submit"] == "hide"){ ?>
                        <form class="form-inline" action="/index.php"  method="get">
                        <input type=hidden name=view value=<?php echo MAIN_VIEW; ?>>
                        <button type=submit name="submit" value=unhide class="btn btn-info">
                            <h8><em class=selthin><i class='fas fa-1x fa-check-circle'></i>UnHide Marketing</em></h8>
                        </button>
                        </form>
                     <?php } else { ?>
                        <form class="form-inline" action="/index.php"  method="get">
                        <input type=hidden name=view value=<?php echo MAIN_VIEW; ?>>
                        <button type=submit name="submit" value=hide class="btn btn-info">
                            <h8><em class=selthin><i class='fas fa-1x fa-ban'></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hide Marketing</em></h8>
                        </button>
                        </form>
                      <?php } ?>
                    </td>
                    </tr>
              <?php }
              else {?>
                    <tr><td> </td><td><h4>No Sim</h4></td></tr>
              <?php } ?>
            </thead>
         <tbody>
         <?php if (isset($sel_num) ) {
                $smslog = new SmsLog();
                if (isset($_GET["submit"]) && $_GET["submit"] == "hide"){
                    $logs = $smslog->getChatLogForMyNumber($sel_num, true);
                }
                else {
                    $logs = $smslog->getChatLogForMyNumber($sel_num, false);
                }
                foreach ($logs as $log) {
           ?>
            <tr>
            <td class="break" colspan=2>
            <form class="form-inline" action="/index.php"  method="get">
                    <input type=hidden name=view value=<?php echo SMS_DETAIL; ?>>
                    <input type=hidden name=my_number value=<?php echo $log['my_number'];; ?>>
                    <input type=hidden name=there_number value=<?php echo $log['there_number']; ?>>
                    <button type=submit value=submit style="border: none;background-color: var(--tb3);text-align: left;">
                    <?php 
                        if ($log['direction'] == 1) {
                            echo "<h7 class='text-left'>".$log['there_number']."&nbsp;&nbsp;&nbsp;".substr($log['changedOn'], 5, 11)."</h7><br/>"."<h7>".$log['sms']."</h7>";
                        }
                        else {
                            echo "<h7 class='text-left'>".$log['there_number']."&nbsp;&nbsp;&nbsp;".substr($log['changedOn'], 5, 11)."</h7><br/>"."<h5>".$log['sms']."</h5>";
                        }
                    ?>
                    </button>
            </form>
            </td>
            </tr>
         <?php } } ?>
          </tbody>
        </table>   
</div>
     
<div class="col-lg-3 col-mg-3 col-sm-12 col-12">
        <h4>Health</h4>
        <hr/>
        <?php
            if ($sel_dev != null && $sel_dev['uuid'] != null) {
        ?>
        <iframe class="embed-responsive-item" scrolling="no" frameborder="0" id="<?php echo $sel_dev['uuid']; ?>0" style="display: block"
            src="https://app.ibeyonde.com/views/graph/gsm_health.php?uuid=<?php echo $sel_dev['uuid']; ?>"> 
        </iframe>
        <?php
            }
            else {
                echo "<h9>Activate a device</h9>";
            }
        ?>
            
        <h4>Help</h4>
        <hr/>
        <h6> You can get upto 5 mobile numbers of differrent types. Select at least one to be installed on the online emulator.
        To install a number just check the checkbox in front of the number and press install. 
        Once install you can see the "No Sim" signal go away and replaced by the selected number.
        </h6>
        <br/><br/>
     <?php if (sizeof($my_numbers) == 0 ) { ?>
        <h6> You do not have any number to attach to the emulator.</h6>
        <br/><br/>
        <h6>Acquire a number to attach.</h6>
        <br/><br/>
        <h6> You have three option to choose form.</h6>
        <br/><br/>
        <ul>
         <li><h6>1. Virtual Number</h6></li>
         <li><h6>2. A hosted real number </h6></li>
         <li><h6>3. A number you attach</h6></li>
        </ul>
        <br/><br/>
        <h6> Click on add number in left column to know more.</h6>
        <br/><br/>
     <?php } 
     if ($virtual){ ?>
        <h6> The number displayed in green colour are virtual numbers.</h6>
        <br/><br/>
        <h6>No real SMS will be sent or received. </h6>
        <br/><br/>
        <h6>You can use these number to test and explore full online functions. </h6>
        <br/><br/>
        <h6>Virtual numbers give you a send box to test and play. </h6>
        <br/><br/>
     <?php } 
     if ($real) { ?>
        <h6> The numbers displayed in blue are real numbers.</h6>
        <br/><br/>
        <h6>Real SMS will be sent or received when trying out functions using these numbers. </h6>
        <br/><br/>
     <?php } ?>
</div>  
</div>
   
</div> 

<?php 
include(__ROOT__.'/views/_footer.php');
?>