<?php
define('__SMSMROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/libraries/aws.phar');
include(__ROOT__.'/views/_header.php');
include_once(__ROOT__ .'/classes/core/Log.php');
include_once(__ROOT__ .'/classes/core/Mysql.php');


$_SESSION['log'] = new Log("info");
$user_id=$_SESSION['user_id'];

require_once(__ROOT__.'/classes/sms/SmsUtils.php');

$user_id = $_SESSION ['user_id'];

$SU = new SmsUtils();

if( isset($_POST['refresh'])){
    list($hk, $exp) = $SU->delHostKey($user_id);
}
list($hk, $exp) = $SU->getHostKey($user_id);
error_log("H".$hk."K".$user_id.strlen($hk));
if (strlen($hk) != 64){
    list($hk, $exp) = $SU->createHostKey($user_id);
}
?>
  
<script>
function copyToClipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
  }
</script>
<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/user_menu.php'); ?>
<br/>
    <h4>Host Key</h4>
    <br/> 
    <table width="100%">
    <tr>
    <td><h7 id="p1"><?php echo $hk; ?></h7>
    </td>
    <td>&nbsp;&nbsp;&nbsp;<button onclick="copyToClipboard('<?php echo $hk; ?>')">copy</button>
    </td>
    <td>
    <form action="/index.php?view=host_key" method="post">
        <button type="submit" name="refresh" value="refresh">refresh</button>
    </form>
    </td>
    </tr>
    </table>
    <br/> 
    <hr class=thin/>
    <h7>The host key is your's companies secret, so you need to protect it and not share it with any untrusted party. 
  Authenticate the SimOnline.in API calls using this key.</h7>
  <h7>
    This key expires on <?php echo $exp; ?>
    </h7>
 
    <div class="row">
        <br /> <br /> <br /> <br />
        <br /> <br /> <br /> <br />
    </div>
 
</div>
<?php 
include(__ROOT__.'/views/_footer.php');
?>
</body>
</html>