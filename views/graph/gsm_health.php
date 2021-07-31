<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<div id="healthchart" width="400" height="300"></div>

<?php 
define ( '__ROOT__', dirname(dirname ( dirname ( __FILE__ ))));
require_once(__ROOT__.'/classes/sms/SmsLog.php');
require_once(__ROOT__.'/classes/Utils.php');
session_start();
$uuid = $_GET ['uuid'];
$now=Utils::datetimeNow("Asia/Calcutta");

$smslog = new SmsLog();
$hlth = $smslog->getGsmHealth($uuid, strtotime('-1 hour', $now));
$data = array();
if (isset($hlth) && count($hlth) > 0){
    $i=0;
    foreach ($hlth as $hlth){
        $data[$i]['date'] = $hlth['changedOn'];
        $data[$i]['alive'] = $hlth['alive'];
        $i++;
    }
}
else {
    echo "<br/><br/><font color=red><b>ERROR Device Down</b></font>";
    die;
}
//echo json_encode($data);
?>

<script type="text/javascript">
<!--

new Morris.Line({
  element: 'healthchart',
  data : <?php echo json_encode($data); ?>,
  xkey: 'date',
  ykeys: ['alive'],
  labels: ['gsm'],
  xLabels: ['1min'],
  resize: true,
  pointSize: 2,
  hideHover: 'auto',
  behaveLikeLine: true,
  resize: true,
  lineColors:['orange']
  
});

//-->
</script>
