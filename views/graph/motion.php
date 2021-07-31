<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<div id="motionChart"></div>

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

$data = array();
$stats = $utils->retriveMotion($uuid, strtotime('-1 day', $now));
if (isset($stats) && count($stats) > 0){
    $i=0;
    foreach ($stats as $stat){
        $data[$i]['date'] = $stat['timeslice'];
        $data[$i]['motion'] = $stat['count'];
        $i++;
    }
}
else {
    echo "<br/><br/><br/><br/><br/><b>No analytics for today for device today</b>";
    die;
}
?>
    
    <script type="text/javascript">
    <!--
    
    new Morris.Line({
      element: 'motionChart',
      data : <?php echo json_encode($data); ?>,
      xkey: 'date',
      ykeys: ['motion'],
      labels: ['Motion'],
      xLabels: ['10min'],
      resize: true,
      pointSize: 2,
      xLabels: ['10min'],
      resize: true,
      pointSize: 2,
      hideHover: 'auto',
      behaveLikeLine: true,
      resize: true,
      lineColors:['blue']
      
    });
    
    //-->
    </script>