<?php
include_once(__ROOT__ . '/classes/wf/def/WfLayout.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/def/SmsWfNode.php');
require_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/sms/SmsImages.php');
include_once(__ROOT__ .'/classes/core/Log.php');
include_once(__ROOT__ . '/classes/wf/data/WfData.php');

$log = $_SESSION['log'] = new Log("info");
$user_id= $_SESSION['user_id'];

$submit = isset($_GET['submit']) ? $_GET['submit'] : (isset($_POST['submit']) ? $_POST['submit'] : null);
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : (isset($_POST['bot_id']) ? $_POST['bot_id'] : null);
$state = isset($_GET['state']) ? $_GET['state'] : (isset($_POST['state']) ? $_POST['state'] : null);

$WFDB = new WfMasterDb();

if ($submit == "delete"){
    $WFDB->deleteNode($bot_id, $state);
}
else if ($submit == "set_css"){
    $css = $_GET['css'];
    $WFDB->updateCss($user_id, $bot_id, $css);
}

$wfl = new WfLayout($user_id, $bot_id);
$wfl->assignCoordinates();

$nodes = $wfl->getNodes();
$edges = $wfl->getEdges();

$star_nodes=array();
$normal_nodes = array();
$state_x = 0;
$state_y=0;
foreach($nodes as $node){
    if(strpos($node->getState(), '%') === 0){
        $star_nodes[] = $node;
    }
    else if ($node->getState() == 'start'){
        //skip
        continue;
    }
    else {
        $normal_nodes[] = $node;
    }
}
$y_max = $wfl->fanout();
$x_max = $wfl->length();

$wf = $WFDB->getWorkflow($bot_id);
$mult_factor=100;
$container_y = $y_max*$mult_factor > 1000 ? 1000 : $y_max*$mult_factor;

include(__ROOT__.'/views/_header.php');


$SU = new SmsImages();
$Limages = $SU->listImages($bot_id);
$count = $SU->imageCount($bot_id);


$kb = new WfData($user_id, $bot_id);
?>
<style>

/* Variables */
:root {
  --thumbnail-width: 400px;
  --thumbnail-height: 600px;
  --thumbnail-zoom: 0.25;
}

</style>

<link rel="stylesheet" href="/css/thumbnail.css">

<body>
<div class="container top">
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_menu.php'); ?>
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_body_menu.php'); ?>

    <div class="row">
        <div class="col-lg-12 col-md-12">
        <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_graph.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
        <h4>Catalog Graph</h4>
        <p>Click on the node to make changes to look and feel. A page consists of a
                <a href="/catalog-maker/docs/index.html#messages" target="_blank">message</a> and a set of
                <a href="/catalog-maker/docs/index.html#messages" target="_blank">actions</a>.
                </p>

        <div class="col-lg-12 col-md-12">
          <div class="container-fluid" id="svgcontainer" style="border: 1;height: <?php echo $container_y > 600 ? 600: $container_y; ?>;width: 100%;overflow: auto;scrollbar-width: none;">
            <svg width="<?php echo $x_max*$mult_factor; ?>" height="<?php echo $y_max*$mult_factor; ?>" >
            <?php
                    try {
                        foreach($edges as $edge){
                            $end = $wfl->getCoordinate($edge[1]);
                            if ($end[0] == -1)continue;
                            $start = $wfl->getCoordinate($edge[0]);
                            $sx = $start[0] *$mult_factor ;
                            $sy =  $start[1]*$mult_factor;
                            $ex = $end[0] *$mult_factor;
                            $ey =  $end[1]*$mult_factor;

                            if ($sx > $ex){
                                echo '<line x1="'.$sx .'" y1="'. $sy .'" x2="'. $ex .'" y2="'. $ey .'" style="opacity: 0.6;stroke: grey;stroke-width: 2;transform: translateY(-2px);" />';
                            }
                            else {
                                echo '<line x1="'.$sx .'" y1="'. $sy .'" x2="'. $ex .'" y2="'. $ey .'" style="opacity: 0.6;stroke: orange;stroke-width: 2;transform: translateY(2px);" />';
                            }
                        }
                    }
                    catch (Exception $e){
                        $_SESSION['message'] = $e->getMessage();
                    }
                    foreach($normal_nodes as $node){
                        list($node_x, $node_y) = $node->getCoordinate();
                        $x = $node_x*$mult_factor;
                        $y = $node_y*$mult_factor;
                        $state=$node->getState();
                        $len = strlen($state);
                        $x_text = $x - $len * 6;
                        $y_text = $y - 10;
                        echo '<a href="/index.php?view=workflow_node&bot_id='.$bot_id.'&state='.$state.'&submit=edit">';
                        echo '<circle cx="'. $x .'" cy="'. $y .'" r="10" stroke="green" stroke-width="5" fill="green"/>';
                        echo '<text x="'. $x_text .'" y="'. $y_text .'" fill="blue">'. $state .'</text>';
                        echo '</a>';
                    }
            ?>
            </svg>
          </div>
        </div>
    </div>


</div> <!-- container -->
<script>
$(function() {
    $(window).on("unload", function() {
       var scrollTop = $("#svgcontainer").scrollTop();
       localStorage.setItem("scrollTop", scrollTop);
       var scrollLeft = $("#svgcontainer").scrollLeft();
       localStorage.setItem("scrollLeft", scrollLeft);
    });
    if(localStorage.scrollPosition) {
       $("#svgcontainer").scrollTop(localStorage.getItem("scrollTop"));
       $("#svgcontainer").scrollLeft(localStorage.getItem("scrollLeft"));
    }
 });
</script>
<?php
include(__ROOT__.'/views/_footer.php');
?>
</body>
</html>