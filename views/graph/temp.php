<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<div id="tempchart" width="400" height="300"></div>

<?php 
define ( '__ROOT__', dirname(dirname ( dirname ( __FILE__ ))));
require_once(__ROOT__.'/classes/Utils.php');
require_once(__ROOT__.'/classes/UserFactory.php');

session_start();
$uuid = $_GET ['uuid'];
$timezone = $_GET['timezone'];
$user = UserFactory::getUser($_SESSION['user_name'], $_SESSION['user_email']);
$device = $user->getDevice($uuid);
$now=Utils::datetimeNow($device->timezone);
$utils = new Utils();

$temps = $utils->retriveTempStats($uuid, strtotime('-1 day', $now));
$data = array();
if (isset($temps) && count($temps) > 0){
    $i=0;
    foreach ($temps as $temp){
        $data[$i]['date'] = $temp['time'];
        $data[$i]['temp'] = $temp['temp'];
        $data[$i]['humid'] = $temp['humid'];
        $i++;
    }
}
else {
    echo "<b><br/><br/><br/><br/><br/>No temperature reading found for today</b>";
    die;
}
//echo json_encode($data);
?>

<script type="text/javascript">
<!--

new Morris.Line({
  element: 'tempchart',
  data : <?php echo json_encode($data); ?>,
  xkey: 'date',
  ykeys: ['temp', 'humid'],
  labels: ['Temp', 'Humidity'],
  xLabels: ['10min'],
  resize: true,
  pointSize: 2,
  hideHover: 'auto',
  behaveLikeLine: true,
  resize: true,
  lineColors:['orange', 'lightblue']
  
});

//-->
</script>
