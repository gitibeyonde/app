<?php
define ( '__ROOT__', dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ));
include_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
require_once (__ROOT__ . '/classes/sms/SmsImages.php');
include_once(__ROOT__ . '/classes/sms/SmsMinify.php');
include_once (__ROOT__ . '/classes/core/Log.php');

$log = $_SESSION ['log'] = new Log ( 'debug' );
$bot_id = $_GET ['bot_id'];
$type = $_GET ['type'];

$WFDB = new WfMasterDb ();
$custom_wf = $WFDB->getWorkflow ( $bot_id );

$SI = new SmsImages();
$lg = $SI->logo($custom_wf['bot_id']);

$min = new SmsMinify();

if ($type == "otp"){
    $url = "https://".$min->createMicroAppUrlOtp($user_id, $bot_id);
}
else {
    $url = "https://".$min->createMicroAppUrl($user_id, $bot_id);
}
?>
<table style="background-color: white; width: 100%; height: 1000px;">
    <tr>
        <td colspan=2 style="text-align: center;padding: 20px;"><img src="<?php echo $lg; ?>" width="200px">&emsp;&emsp;
        <font color=black size=7><?php echo $custom_wf['name']; ?></font></td>
    </tr>
    <tr>
        <td colspan=2></td>
    </tr>
    <tr>
        <td colspan=2 style="text-align: center;"><img src="/classes/core/QRCode.php?f=png&s=qr-q&d=<?php echo urlencode($url); ?>&sf=8&ms=r&md=0.8" width="600px"></td>
    </tr>
    <tr>
        <td colspan=2 style="text-align: center;"><h4>
                <a href="http://<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a>
            </h4></td>
    </tr>
</table>
