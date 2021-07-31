<?php
$user_id= $_SESSION['user_id'];

$submit = isset($_GET['submit']) ? $_GET['submit'] : (isset($_POST['submit']) ? $_POST['submit'] : null);
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : (isset($_POST['bot_id']) ? $_POST['bot_id'] : null);
$state = isset($_GET['state']) ? $_GET['state'] : (isset($_POST['state']) ? $_POST['state'] : null);

if ($user_id==null || $bot_id == null){
    $_SESSION['message'] = "Session expired";
    header("Location:  /index.php", true, 307);
}

include_once(__ROOT__ . '/classes/wf/def/WfLayout.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/def/SmsWfNode.php');
require_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/sms/SmsImages.php');
include_once(__ROOT__ .'/classes/core/Log.php');
include_once(__ROOT__ . '/classes/wf/data/WfData.php');

$log = $_SESSION['log'] = new Log("info");

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
  --thumbnail-width: 480px;
  --thumbnail-height: 710px;
  --thumbnail-zoom: 0.5;
}

</style>

<link rel="stylesheet" href="/css/thumbnail.css">

<body>
<div class="container top">
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_menu.php'); ?>
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_body_menu.php'); ?>

<div class="row">
    <div class="col-lg-12 col-md-12">
    <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_pages.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
    <h4>Catalog Pages</h4>
    <p>Click on the thumbnail of the page to make changes to look and feel. A page consists of a
            <a href="/catalog-maker/docs/index.html#messages" target="_blank">message</a> and a set of
            <a href="/catalog-maker/docs/index.html#messages" target="_blank">actions</a>.
            </p>

    <form class="form-inline" action="/index.php"  method="get" style="float: left;">
        <select  class="form-control" name="css"  style="height: 1.5rem; padding: 1px;margin: 1px;">
               <option value="light" <?php echo ($wf['css']=="light" ? "selected" : ""); ?>>light</option>
               <option value="black" <?php echo ($wf['css']=="black" ? "selected" : ""); ?>>black</option>
               <option value="white" <?php echo ($wf['css']=="white" ? "selected" : ""); ?>>white</option>
               <option value="blue" <?php echo ($wf['css']=="blue" ? "selected" : ""); ?>>blue</option>
           </select>
        <input type=hidden name=view value="<?php echo WIZ_WF_PAGES; ?>">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type="hidden" name="state" value="<?php echo $state; ?>">
        <button  type="submit" name="submit" value="set_css"  class="btn btn-sim4">Select Skin</button>
    </form>

    <div class="table-responsive">
        <table class="table">
            <tr>
            <td>
                <form action="/index.php"  method="get" style="float: left;">
                <input type=hidden name=view value="workflow_node">
                <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                <button type="submit" name="submit" value="add"  style="width: 100px; height: 350px;">+ Click to add page</button>
                </form>
            </td>
            <?php
            foreach($normal_nodes as $node){
                ?>
            <td>
             <a href="/index.php?view=workflow_node&bot_id=<?php echo $bot_id; ?>&state=<?php echo $node->getState();?>&submit=edit">
                    <img  class='img-fluid' src='/img/td_yellow_circle.png' width='8px'>
                         <?php echo $node->getState();?>
              <div class="thumbnail-container">
                <div class="thumbnail">
                  <iframe src="/views/sms/wf_display.php?m=<?php echo urlencode(base64_encode(SmsWfUtils::join ($node->getMessage())."^^".$bot_id."^^".$user_id."^^".$wf['css'])); ?>"
                               onload="this.style.opacity = 1" frameborder="0"> </iframe>
                </div>
              </div>
              </a>
            </td>
            <?php } ?>
            </tr>
        </table>
     </div>
    </div> <!-- column -->
</div> <!-- row -->
<h6>**Click on the page to goto page editor. </h6>

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