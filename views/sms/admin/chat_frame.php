<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<script  type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"></script>
<script  type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script  type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script  type="text/javascript" src="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

<link href="//code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" href="//www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<style>
       
:root {
  --tc1: #343d46;
  --tc2: #4f5b66;
  --tc3: #65737e;
  --tc4: #a7adba;
  
  --tp1: #2e003e;
  --tp2: #3d2352;
  --tp3: #3d1e6d;
  --tp4: #8874a3;
  
  --tb1: #ffe7c7;
  --tb2: #fbeaea;
  --tb3: #fff5ee;
  --tb4: #fffafa;
}

.table-fixed-phone {
    height: 450px;
    display: block;
    width: 100%;
    overflow-y: hidden;
}

.chat-input-card {
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
    border-radius: 11px;
    background: var(--tb3);
    position: relative;
    color: var(--tc2);
    padding: 10px;
}


.chat-display-card {
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
    border-radius: 11px;
    background: var(--tb1);
    position: relative;
    color: var(--tc4);
    padding: 10px;
}

h8 {
    font-size: 14px;
    color: var(--tp1); 
}

h9 {
    font-size: 14px;
    color:  var(--tp2);
}

</style>

<script  type="text/javascript">
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
session_start();

define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
include_once(__ROOT__ .'/classes/core/Log.php');
include_once (__ROOT__.'/classes/wf/SmsWfProcessor.php');
include_once (__ROOT__.'/classes/wf/SmsWfUtils.php');


$sms=null;
$resp=null;


$_SESSION['log'] = new Log("info");

if (!isset($_POST['action'])){
    $_SESSION["random_mobile"] = get_num();
    $_SESSION["chats"] = array();
    SmsWfUtils::processEmbeddedCommand ( "%%CLEARALL%%", 95, 0, $_SESSION["random_mobile"]); 
}
else {

    if($_POST['action']=="clear"){
        $_SESSION["chats"]=null;
        SmsWfUtils::processEmbeddedCommand ( "%%CLEARALL%%", 95, 0, $_SESSION["random_mobile"]); 
    } 
    else if (isset($_POST['sms'])){
        $sms = $_POST['sms'];
        add_sms(true, $sms);
        $resp = SmsWfProcessor::processChat(95, "0", $_SESSION["random_mobile"], $sms);
        add_sms(false, $resp);
    }
}

function get_num(){
    $ip = $_SERVER['REMOTE_ADDR'];
    $ip = str_replace(".", "1", $ip);
    $len = strlen($ip);
    return "912222".substr($ip, 0, 6);
}

function add_sms($in, $sms){
    if ($sms == "") return;
    $chats = $_SESSION["chats"];
    array_push($chats, array($in, $sms));
    if (count($chats)> 5){
        array_shift($chats);
    }
    error_log(SmsWfUtils::flatten($chats));
    $_SESSION["chats"] = $chats;
}

?>

<div class="container" style="background: black">
      <div class="chat-display-card">
        <table class="table table-fixed-phone" width="100%"> 
          <thead style="background: var(--tb2)">
          <tr>
             <td>
                <h5 class=selthin>SO Hospital Appointment</h5>
              </td>
          </tr>
          </thead>
           <tbody>
            <?php foreach ($_SESSION["chats"] as $msgs) {
                if ($msgs[0]){
                ?>
                 <tr>
                 <td style="float: right;"><h9><i><?php echo $msgs[1];  ?></i></h9></td>
                 </tr>
             <?php } else { ?>
                 <tr>
                 <td style="float: left;"><h8><?php echo $msgs[1];  ?></h8></td>
                 </tr>
             <?php }} ?>
           </tbody>
        </table>
        <div class="chat-input-card">
          <form class="form-inline"  id="chat_form" action="//<?php echo $_SERVER ['SERVER_NAME']; ?>/views/sms/admin/chat_frame.php" method="post">
              <input type=text name="sms" size="30" onkeyup="countChar(this)" placeholder="Enter Sms" autofocus required></input>
              <input type=hidden name=category value="healthcare">
              <button type="submit" name="action" value="sms" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
          </form>
        </div> 
      </div>
      <br/>
 </div>
 <script src="//kit.fontawesome.com/f882775bc0.js?<?php echo mt_rand(); ?>" crossorigin="anonymous"></script>