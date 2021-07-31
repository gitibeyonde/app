<?php
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/core/Icons.php');

$Icons = new Icons();
$log=$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

$user_id = $_SESSION['user_id'];

$view = isset($_GET['view']) ? $_GET['view'] : (isset($_POST['view']) ? $_POST['view'] : null);
$submit = isset($_GET['submit']) ? $_GET['submit'] : (isset($_POST['submit']) ? $_POST['submit'] : null);
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : (isset($_POST['bot_id']) ? $_POST['bot_id'] : null);
$state = isset($_GET['state']) ? $_GET['state'] : (isset($_POST['state']) ? $_POST['state'] : null);

$_GET['view'] = $view;

$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);

if ($submit == "set_css"){
    $css = $_GET['css'];
    $WFDB->updateCss($user_id, $bot_id, $css);
}

$log->debug("Node View=".$view." botid=".$bot_id." state=".$state. " submit=".$submit);

include_once(__ROOT__ . '/views/_header.php');

if ($view == "workflow_node") {
    echo '<link rel="stylesheet" href="/css/wfe.css">';
    echo '<body onload="initDoc();">';
    echo '<div class="container-fluid top">';

    include_once(__ROOT__.'/views/sms/wiz/wiz_wf_node_message.php');
}
else if ($view == "workflow_action") {

    echo '<body>';
    echo '<div class="container-fluid top"> ';

    include_once(__ROOT__.'/views/sms/wiz/wiz_wf_node_action.php');
}
else if ($view == "workflow_help") {

    echo '<body>';
    echo '<div class="container-fluid top"> ';

    include_once(__ROOT__.'/views/sms/agent_wf_node_help.php');
}
else {
    $log->error("Unknown view");
    die;
}

echo '</div>';

include_once(__ROOT__ . '/views/_footer.php');

echo '</body>';
?>