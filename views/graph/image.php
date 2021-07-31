<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<div id="imageChart"></div>

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

$means = $utils->retriveImageStats($uuid, strtotime('-1 day', $now));
$data = array();
if (isset($means) && count($means) > 0){
    $i=0;
    foreach ($means as $mean){
        $data[$i]['date'] = $mean['time'];
        $data[$i]['mean'] = $mean['mean'];
        $data[$i]['rms'] = $mean['rms'];
        $data[$i]['var'] = $mean['var']/10;
        $data[$i]['median'] = $mean['median'];
        $i++;
    }
}
else {
    echo "<br/><br/><br/><br/><br/><b>No analytics for today for device</b>";
    die;
}
?>
    
    <script type="text/javascript">
    <!--
    
    new Morris.Line({
      element: 'imageChart',
      data : <?php echo json_encode($data); ?>,
      xkey: 'date',
      ykeys: ['mean', 'rms', 'var', 'median'],
      labels: ['Mean', 'Rms', 'Var', 'Median' ],
      xLabels: ['10min'],
      resize: true,
      pointSize: 2,
      hideHover: 'auto',
      behaveLikeLine: true,
      resize: true,
      lineColors:['red', 'green', 'blue', 'yellow']
    });
    
    //-->
    </script>