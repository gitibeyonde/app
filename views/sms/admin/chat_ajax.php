<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=yes">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script  type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/css/wf_chat.css">
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
 </head>
 <body>
<?php
session_start();

define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
include_once(__ROOT__ .'/classes/core/Log.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/wf/data/WfData.php');
include_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/def/SmsWfNode.php');
require_once (__ROOT__ . '/classes/sms/SmsImages.php');
require_once(__ROOT__.'/classes/sms/SmsMinify.php');
include_once(__ROOT__ .'/classes/Utils.php');
require_once(__ROOT__.'/classes/sms/SmsPayment.php');
include_once(__ROOT__ .'/classes/core/Icons.php');

$log = $_SESSION['log'] = new Log("info");
$_SESSION["random_mobile"] = get_num();


function get_num(){
    $ip = ip2long($_SERVER['REMOTE_ADDR']);
    $len = strlen($ip);
    if ($len > 11){
        $ip = $ip.substring(0, 11);
        error_log("IP=".$ip);
    }
    else {
        $ip = str_pad($ip, 11, "0", STR_PAD_LEFT);
        error_log("PADDED IP=".$ip);
    }
    return "1".$ip;
}

$bot_id=isset($_GET['bot_id']) ? $_GET['bot_id'] : "fcca525e27b";
$user_id=isset($_GET['user_id']) ? $_GET['user_id'] : 95;

SmsWfUtils::processEmbeddedCommand ( "%%CLEARALL%%",  $user_id, $bot_id, $_SESSION["random_mobile"]);
$wfdb = new WfMasterDb();
$DBWF = $wfdb->getWorkflow($bot_id);

$SN = $wfdb->getNode($bot_id, "start");
$start = $SN['message'];
$log->trace("Start message=".$start);


$SI = new SmsImages();
$logo= "/img/ico192.png";
$lg = $SI->logo($bot_id);
if ($lg != null){
    $logo = $lg;
}

$pay_url="https://deltacatalog.com/views/sms/wf_pay_razorpay.php?bot_id=".$bot_id."&user_id=".$user_id."&t=".$t."&amnt=";
$Pp = new SmsPayment();
$Luser = $Pp->getUserData($user_id);
$merchant_name = $Luser['user_name'];
$merchant_phone = $Luser['user_phone'];
$merchant_email = $Luser['user_email'];
$qr = "https://www.deltacatalog.com/classes/core/QRCode.php?f=png&s=qr-q&d=".$bot_id.":".$t."&sf=8&ms=r&md=0.8";

?>
<div class="container top-container" id="container">
             <nav class="fixed-top">
                 <div class="panel-group" id="accordion1">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                               <table width="100%">
                                 <tr>
                                 <td  style="text-align: right;"><img src="<?php echo $logo; ?>"  class="img-fluid main-logo">
                                 </td>
                                 <td><h1><?php echo $DBWF['name'].$_SESSION["random_mobile"]; ?></h1>
                                 </td>
                                 <td style="text-align: right;"> 
                                 <a class="user-guide-panel" data-toggle="collapse"  data-target="#collapseTwo">
                            		<img src="/img/qr-code.png" class="img-fluid main-logo2">
                                 </a>
                                 </td>
                                 <td style="text-align: center;"> 
                                 <a class="user-guide-panel" data-toggle="collapse"  data-target="#collapseOne">
                           			<img src="/img/address.png" class="img-fluid main-logo2">
                                 </a>
                                 </td>
                                 </tr>
                               </table>
                        </div>
                      <div id="collapseOne" class="panel-collapse collapse">
                        <div class="panel-body"  style="text-align: right;">
                            Contact Information:<br/>
                            <a href="tel:<?php echo $merchant_phone; ?>">&emsp;Call:&emsp;<?php echo $merchant_phone; ?> </a><br/>
                            <a href="sms:<?php echo $merchant_phone; ?>">&emsp;Sms:&emsp;<?php echo $merchant_phone; ?> </a><br/>
                            <a href="mailto:<?php echo $merchant_email; ?>">&emsp;Email:&emsp;<?php echo $merchant_email; ?></a><br/>
                            &emsp;Transaction Id:&emsp;<?php echo $t; ?>
                          </ul>
                        </div>
                      </div>
                      <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="panel-body"  style="text-align: right;padding: 20px;">
                            <img src="<?php echo $qr;?>" width="100%">
                        </div>
                      </div>
                  </div>
               </div>
            </nav>
      <div class="chat-display-card">
         <table id="chat_table" > 
          <tbody id="chat_tbody">
           <tr><td><?php echo $start; ?></td></tr>
         </tbody>
        </table>
        <div class="chat-input-card">
          <form id="chat_form" class="chat-form form-control" action="//<?php echo $_SERVER ['SERVER_NAME']; ?>/views/sms/admin/chat.php" method="post" autocomplete="off">
              <div class="row">
                <div class="col float-right">
                  <input type=text id="sms" name="sms" size="29" onkeyup="countChar(this)" placeholder="Enter text"  autofocus required></input>
                  <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                  <input type=hidden name=user_id value="<?php echo $user_id; ?>">
                  <input type=hidden name=random_mobile value="<?php echo $_SESSION["random_mobile"]; ?>">
                </div>
                <div class="col">
                  <button class="form-control" type="submit" name="action" value="sms" class="btn">Send</button>
                </div>
              </div>
          </form>
        </div> 
      </div>
 </div> 
<script type="text/javascript">
    var frm = $('#chat_form');
    var tbl = $('#chat_table');
    var tbdy = $('#chat_tbody');

    frm.submit(function (e) {

        e.preventDefault();

        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function (data) {
                var resp = data.trim();
                tbdy.append('<tr><td style="float: right;padding: 5px;"><h9>'+jQuery("#sms").val()+'</h9>&nbsp;&nbsp;<img src="/img/guest.png" width="12px"></td></tr>');
                if (resp.length > 1){
                    tbdy.append('<tr><td style="float: left;padding: 5px;"><img src="/img/jarvis.png"  width="12px">&nbsp;&nbsp;<h8>'+data.trim()+'</h8></td></tr>');
                }
                jQuery("#sms").val('');
                var totalRowCount = $("#chat_tbody tr").length;
                if (totalRowCount > 20){
                    $('#chat_tbody tr:first').remove();
                    $('#chat_tbody tr:first').remove();
                }
                $("#chat_table").scrollTop($("#chat_tbody tr").length*4000);
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            },
        });
    });

    
</script>

 </body>
 </html>