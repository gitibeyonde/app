<?php
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/def/SmsWfNode.php');
include_once(__ROOT__ . '/classes/wf/def/WfLayout.php');
include_once(__ROOT__ . '/classes/wf/utils/WfEdit.php');

$log = $_SESSION['log'] = new Log ("trace");
$user_id=$_SESSION['user_id'];

$WFDB = new WfMasterDb();

if ($submit != "add"){ // if it is not a new node, the read it from DB
    $wfnode = $WFDB->getNode($bot_id, $state);
}

$message = isset($_POST['message']) ? $_POST['message'] : $wfnode['message'];
$help = isset($_POST['help']) ? $_POST['help'] : $wfnode['help'];
$state = isset($_POST['state']) ? $_POST['state'] : $wfnode['state'];

$log->debug("Bot id=".$bot_id." submit=".$submit." message=".$message);

if ($submit == "add" && $state != null && $message != null){
    $state = $_POST['state'];
}
else if ($submit == "update"){
    $actions_str = $wfnode['actions'];
    if (isset($_POST['action_name'])){ // if you have been to action edit
        $name_array=$_POST['action_name'];
        $desc_array=$_POST['action_desc'];
        $state_array=$_POST['next_state'];
        $extract_array=$_POST['choice_extract'];
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
}
else if ($submit == "set_css"){
    $css = $_GET['css'];
    $WFDB->updateCss($user_id, $bot_id, $css);
}

$wf = $WFDB->getWorkflow($bot_id);
$log->debug(print_r($wf, true));

$isMath = $wf['status'] & 8;
$isHTML = $wf['status'] & 4;
$type = $isHTML == true ? "HTML" : ($isMath == true  ? "MATH" : "TEXT");
?>

<link rel="stylesheet" href="/css/wiz/editor.css">

<link rel="stylesheet" href="/css/thumbnail.css">

<script src="/js/wiz/editor.js"></script>

<div class='container-fluid'>


    <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_editor.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
    <?php include_once(__ROOT__.'/views/sms/wiz/wiz_wf_node_menu.php'); ?>

    <div class="row">
        <div class="col-lg-9 col-md-9">
            <div class="row">

                <form id="nodeform" name="nodeform" action="/index.php" method="post" onsubmit="return doSubmitNodeForm();">


                   <input type="hidden" name="actions_str" value="<?php echo htmlspecialchars($wfnode['actions']); ?>">
                   <div class="row">

                        <div class="col-12" style="padding-left: 20px;">

                            <h2 class="sel0"><?php echo $wf['name']; ?></h2>

                           <?php include_once(__ROOT__ . '/views/sms/wiz/wiz_wf_node_message_toolbar.php'); ?>

                           <div class="form-group" id="html_content">
                           		<div id="htmlEditorPane" contenteditable="true" onscroll="setScrollPosition();"><?php echo $state==null ? "": $message; ?></div>
                           </div>

                           <input id="message_input" type="hidden" name="message" value="">
                        </div>
                  </div>

                  <input type="hidden" name="help" value="">
                  <input type="hidden" name="bot_id" value="<?php echo $bot_id; ?>">
                  <input type="hidden" id="viewname" name=view value="">

                  <table width="100%">
                      <tr style="background: var(--blue1);">
                      <?php if ($submit != "add") {?>
                      <td style="text-align: center">
                       <button type="submit" id="save_message" name="submit" value="update" class="btn btn-sim1"   onclick="return onClickSubmitButton('<?php echo WORKFLOW_NODE; ?>');">
                       Save(Ctl-S)</button>
                      </td>
                      <?php } else { ?>
                      <td style="text-align: center">
                       <button type="submit" id="save_message" name="submit" value="<?php echo $submit; ?>" class="btn btn-sim1"  onclick="return onClickSubmitButton('<?php echo WORKFLOW_NODE; ?>');">
                       Refresh(Ctl-S)</button>
                      </td>
                      <?php } ?>
                      <td style="text-align: center">
                      <button type="submit" name="submit" value="<?php echo $submit; ?>"  class="btn btn-sim1"  onclick="return onClickSubmitButton('<?php echo WORKFLOW_ACTION; ?>');">
                       Actions</button>
                      </td>
                      <?php if ($wfnode['state'] != "start") { ?>
                      <td style="text-align: center">
                       <button type="submit" name="submit" value="delete"  class="btn btn-sim2"  onclick="return onClickDel('<?php echo WIZ_WF_PAGES; ?>');">
                       <?php echo $Icons->get("trash_can", 1.5, "red"); ?></button>
                      </td>
                      <?php } ?>
                      </tr>
                  </table>
             </form>
         </div>
      </div> <!-- End second Column -->


       <?php include_once(__ROOT__.'/views/sms/wiz/wiz_wf_node_message_right.php'); ?><!-- End third Column -->
    </div> <!-- End Big Row -->


</div> <!-- End fluid Container -->
 <a href="/catalog-maker/docs/index.html#messages" target="_blank">help</a>

<script>
$("#html_content").keyup(function(e) {
    if (e.keyCode == 83 && event.ctrlKey){
        $("#save_message").click();
    }
});


function onClickSubmitButton(view){
    console.log("onClickSubmitButton " + $(view));
    $("#viewname").val(view);

    if (!isHTML){
        setDocMode(false);
    }
    return true;
}


function onClickDel(view){
    if (confirm('Do you really want delete this Node ?')){
        if ("start1" == "<?php echo $state; ?>"){
            alert("Cannot delete start1 node");
            return false;
        }
        else {
            $("#viewname").val(view);
            return true;
        }
    }
    else {
       return false;
    }
}

function doSubmitNodeForm(){
    console.log(">>>> doSubmitNodeForm " + $("#viewname").val());
    if (document.nodeform.switchMode.checked) {
        document.nodeform.switchMode.checked = false;
        //setDocMode(false);
	}
    //remove resize frames added by image resizer
    document.querySelectorAll(".resize-frame,.resizer").forEach((item) => item.parentNode.removeChild(item));

    console.log("Setting message to " + oDoc.innerHTML);
    $("#message_input").val(oDoc.innerHTML);
    return true;
}


enableImageResizeInDiv("htmlEditorPane");
</script>
