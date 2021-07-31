<?php
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/def/SmsWfNode.php');
include_once(__ROOT__ . '/classes/wf/def/WfLayout.php');
include_once(__ROOT__ . '/classes/wf/utils/WfEdit.php');
include_once(__ROOT__ . '/classes/wf/utils/WfUtils.php');

$log = $_SESSION['log'] = new Log('debug');
$user_id=$_SESSION['user_id'];

$log->debug("Action View=".$view." botid=".$bot_id." state=".$state. " submit=".$submit);


$WFDB = new WfMasterDb();

if ($state != null){
    $wfnode = $WFDB->getNode($bot_id, $state);
}
$log->debug("Bot id=".$bot_id);

$count = 0;

$message = isset($_POST['message']) ? $_POST['message'] : $wfnode['message'];
$help = isset($_POST['help']) ? $_POST['help'] : $wfnode['help'];


error_log("Action Message=".$message." Help=".$help." State=".$state);
error_log("Action FORM=".print_r($_POST, true));

if ($submit == "edit_action"){
    $action_index = $_POST['action_index'];
}
else if ($submit == "save_action"){
    $action_index = $_POST['action_index'];
    $action_array = explode("\n", $wfnode['actions']);
    $wfnode = $WFDB->getNode($bot_id, $state);
}
else if ($submit == "delete_action"){
    $action_index = $_POST['action_index'];
    $action_array = explode("\n", $wfnode['actions']);
    $count=0;
    $new_actions=array();
    $del_action = null;
    foreach ($action_array as $action) {
        if ($count != $action_index){
            $new_actions[] = $action;
            error_log("Add action".$action);
        }
        else {
            $del_action = $action;
            error_log("Del action".$action);
        }
        $count = $count + 1;
    }
    if (count($new_actions)==0){
        //no action left, add back the last action
        $new_actions[]=$del_action;
        $_SESSION['message'] = "Cannot delete all the actions, add a action and then delete this action";
    }
    $actions_str = implode("\n", $new_actions);
    $WFDB->saveNode($bot_id, $state, $message, $actions_str, $help);
    $wfnode = $WFDB->getNode($bot_id, $state);
}
else if ($submit == "update"){
    $actions_str = $wfnode['actions'];
    if (isset($_POST['action_name'])){ // if you have been to action edit
        $name_array=$_POST['action_name'];
        $desc_array=$_POST['action_desc'];
        $state_array=$_POST['next_state'];
        $extract_array=isset($_POST['choice_extract']) ? $_POST['choice_extract'] : null;
        $actions_str = WfUtils::checkAction($name_array, $desc_array, $extract_array, $state_array);
    }
    else if(isset($_POST['actions_str'])){ // if you are just submitting the message form without going to action edit
        $actions_str=$_POST['actions_str'];
    }
    $log->debug("Actions=".$actions_str);
    if (strpos($actions_str, "&") !== false){
        $actions_array = explode("\n", $actions_str);
        $WFDB->saveNode($bot_id, $state, $message, $actions_str, $help);
    }
    else {
        $_SESSION['message'] = "Please, add at least one action before saving the node";
    }
    $wfnode = $WFDB->getNode($bot_id, $state);
}

$wf = $WFDB->getWorkflow($bot_id);
error_log("State=".$state);

$isMath = $wf['status'] & 8;
$isHTML = $wf['status'] & 4;

$type = $isHTML == true ? "HTML" : ($isMath == true  ? "MATH" : "TEXT");

$log->debug("Bot id=".$bot_id);
?>

<div class='container-fluid'>

    <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_actions.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
    <?php include_once(__ROOT__.'/views/sms/wiz/wiz_wf_node_menu.php'); ?>

   <div class="row">
    <div class="col-lg-9 col-md-9">
        <div class="row">

           <form id="nodeform" action="/index.php" method="post" style="width: 100%;" onsubmit="return doSubmit();">
            <input type="hidden" id="viewname" name="view" value="">
            <input type="hidden" id="actionindex" name="action_index" value="">
            <input type="hidden" name="bot_id" value="<?php echo $bot_id; ?>">
            <input type="hidden" name="message" value="<?php $message = htmlspecialchars($message); echo $message; ?>">
            <input type="hidden" name="help" value="<?php echo $help; ?>">
            <input type="hidden" name="state" value="<?php echo $state; ?>">

                  <h2>Actions</h2>
                   <?php
                   $count = 0;
                   if ($state != null){
                       $action_array = explode("\n", $wfnode['actions']);
                       error_log("Action Array ".print_r($action_array, true));
                       echo '<div class="container">';
                       echo '<table class="table table-striped">';
                       echo '<thead>';
                       echo '<tr><td>Action</td><td>Pattern</td><td>Label</td><td>Target Page</td><td></td><td></td></tr>';
                       echo '</thead> <tbody>';
                       foreach ($action_array as $action) {
                           parse_str($action, $action_parts);
                           error_log("Action Parts".print_r($action_parts, true));
                           if (!isset($action_parts['action']))continue;
                           $action_name = trim($action_parts['action']);
                           if ($action_name == "unmatched" || $action_name == "picture"){
                               $action_string = "";
                           }
                           else {
                                $action_string = $action_parts[$action_name];
                           }
                           $extract = isset($action_parts["extract"]) ? $action_parts["extract"]: null;
                           $next_state = $action_parts['next_state'];
                           include(__ROOT__ . '/views/sms/wiz/wiz_wf_node_action_display.php');
                           $count = $count + 1;
                       }
                       echo '</tbody> </table>';
                       echo '<hr/>';
                       echo '</div>';
                   }
                   echo '<a href="/catalog-maker/docs/index.html#actions" target="_blank">help</a>';
                   $req = $count == 0 ? "required" : "";
                   if ($submit != "edit_action") { //do not display input form  and bottom menu, if edit is in progress
                       echo '<br/><br/><h2>Add Action</h2>';
                       include(__ROOT__ . '/views/sms/wiz/wiz_wf_node_action_display_form.php');
                   ?>

                  <table width="100%">
			  <tr style="background: var(--blue1);">
			  <td style="text-align: center">
			  <button type="submit" name="submit" value="<?php echo $submit; ?>" class="btn btn-sim1"  onclick="onClick('<?php echo WORKFLOW_NODE; ?>');">
			   Message<?php echo $Icons->get("card_text", 2, "green"); ?></button>
			  </td>
			  <td style="text-align: center">
			  <button type="submit" id="save_action" name="submit" value="update"  class="btn btn-sim1"  onclick="onClick('<?php echo WORKFLOW_ACTION; ?>');">
			   Save(Ctl-S)</button>
			  </td>
			  <?php if ($wfnode['state'] != "start") { ?>
			  <td style="text-align: center">
			   <button type="submit" name="submit" value="delete"  class="btn btn-sim2"   onclick="return onClickDel('<?php echo WIZ_WF_PAGES; ?>');">
			   <?php echo $Icons->get("trash_can", 2, "red"); ?></button>
			  </td>
			  <?php } ?>
			  <tr>
                  </table>
                  <?php } //EDIT NOT ENABLED?>
          </form>
        </div>
     </div>

      <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_node_action_right.php'); ?><!-- End third Column -->
 </div> <!-- End Big Row -->

</div> <!-- End fluid Container -->

<script>

$("#nodeform").keyup(function(e) {
    if (e.keyCode == 83 && event.ctrlKey){
        $("#save_action").click();
    }
});

function onClick(view, count){
    $("#viewname").val(view);
    $("#actionindex").val(count);
}


function onClickDel(view){
    if (confirm('Do you really want delete this Node ?')){
        $("#viewname").val(view);
        $("#actionindex").val(count);
        return true;
    }
    else {
       return false;
    }
}

function doSubmit(){
    console.log($("#actionindex").val());
    console.log($("#viewname").val());
    return true;
}
</script>
