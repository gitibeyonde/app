<?php
include(__ROOT__.'/views/_header.php');
require_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ .'/classes/core/Log.php');

$_SESSION['log'] = new Log("info");
$user_id=$_SESSION['user_id'];

$bot_id=isset($_GET['bot_id']) ? $_GET['bot_id'] : null;

$WFDB = new WfMasterDb();

$view = isset($_GET['view']) ? $_GET['view'] : (isset($_POST['view']) ? $_POST['view'] : null);
$submit = isset($_GET['submit']) ? $_GET['submit'] : (isset($_POST['submit']) ? $_POST['submit'] : null);
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : (isset($_POST['bot_id']) ? $_POST['bot_id'] : null);


if ($submit == "connect"){
    $from_bot_id = isset($_GET['workflow_id']) ? $_GET['workflow_id'] : null;
    $node_state = isset($_GET['state']) ? $_GET['state'] : null;
    $prefix = isset($_GET['prefix']) ? $_GET['prefix'] : null;
    error_log("$from_bot_id, $node_state, $prefix");
    if(strlen($prefix) < 3){
        $_SESSION['message'] = "The prefix should be at least 3 characters, ".$prefix;
    }
    else {
        //move the nodes to this workflow with renamed states
        $WFDB->moveNodes($from_bot_id, $prefix, $bot_id);
        //add a transition to node
        $new_transition = "action=unmatched&unmatched=&next_state=".$prefix."_start";
        $WFDB->addTransition($bot_id, $node_state, $new_transition);
        // copy the BOT KB to this new Bot's KB
        WfDb::copyBotKB($user_id, $from_bot_id, $user_id, $bot_id);
        // copy images
        $Im = new SmsImages();
        $Im->copyImages($from_bot_id, $bot_id);
    }

}


$wf = $WFDB->getWorkflow($bot_id);
$wfs = $WFDB->getWorkflows($user_id);
$nodes = $WFDB->getNodes($bot_id);
?>
<body>
<div class="container-fluid top">
   <div class="row">
        <?php include(__ROOT__.'/views/sms/agent_wf_menu_top.php'); ?>
    </div>

    <div class="row">
        <div class="col-lg-1 col-md-1">

        </div><!-- End first Column -->

        <div class="col-lg-7 col-md-7">
            <br/>
            <br/>
            <h4>Import App</h4>
            <br/>
            <hr/>

            <form action="/index.php" method="get">

              <div class="row" style="padding: 20px;">
               <div class="form-group">
                  <label for="name">Import Workflow:</label>
                   <select name="workflow_id">
                        <option disabled selected value> -select- </option>
                   <?php foreach ($wfs as $wf) {?>
                        <option value="<?php echo $wf['bot_id']; ?>"><?php echo $wf['name']; ?></option>
                   <?php } ?>
                   </select>
               </div>
              </div>


              <div class="row" style="padding: 20px;">
               <div class="form-group">
                  <label for="name">Prefix:</label>
                  <input type="text" class="form-control" name="prefix"  placeholder="prefix" required>
                  <div class="valid-feedback">Valid.</div>
                  <div class="invalid-feedback">Please fill out this field.</div>
               </div>
              </div>

              <div class="row" style="padding: 20px;">
               <div class="form-group">
                  <label for="name">Connect to Node:</label>
                   <select name="state">
                        <option disabled selected value> -select- </option>
                   <?php foreach ($nodes as $node) {
                      if (strpos($node['state'], "%") === 0)continue;///exclude wild card nodes
                       ?>
                        <option value="<?php echo $node['state']; ?>"><?php echo $node['state']; ?></option>
                   <?php } ?>
                   </select>
               </div>
              </div>

             <div class="row" style="padding: 30px;">
               <input type=hidden name=view value="<?php echo WIZ_WF_PAGES; ?>">
               <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
              <button type="submit" name="submit" value="connect"  class="btn btn-sim1">
                    Connect</button>
             </div>
             </form>
         </div>
     </div>

</div>
<?php
include(__ROOT__.'/views/_footer.php');
?>