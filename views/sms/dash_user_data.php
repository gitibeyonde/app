<?php
include_once(__ROOT__ . '/views/_header.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/wf/data/WfUserData.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');

$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

$tabella = "user_data";
$user_id = $_SESSION['user_id'];
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : null;

$kb = new WfUserData($user_id, $bot_id);

$rowid = isset($_GET['rowid']) ? $_GET['rowid'] : null;
$submit = isset($_GET['submit']) ? $_GET['submit'] : null;

if ($submit==null){
    $submit = isset($_POST['submit']) ? $_POST['submit'] : null;
}

error_log("Template action =" . $submit);
if ($submit == "add"){
    $name=$_POST['name'];
    $filename = $_FILES["fileToUpload"]["tmp_name"];
    try {
        $kb->loadWFData($name, $filename);
    }
    catch (Exception $e){
        $_SESSION['message']  = "Upload Failed". $e->getMessage();
    }
}
else  if ($submit == "delete"){
    $tabella=$_GET['tabella'];
    try {
        $kb->deleteWFTable($tabella);
    }
    catch (Exception $e){
        $_SESSION['message']  = "Upload Failed". $e->getMessage();
    }
}

error_log("Botid  =" . $bot_id);

$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);
error_log($bot_id.", ".print_r($wf, true));

$ignore_tables = array("user_data_report", "user_context", "user_phone");
?>


<body>
<div class="container-fluid top">  
   <?php include(__ROOT__.'/views/sms/dash_menu_top.php'); ?>

    <div class="row">
        <div class="col-lg-2 col-md-2">
            <h4><?php echo $wf['name']; ?></h4>
            <h5><?php echo $wf['category']; ?></h5>
        </div><!-- End first Column -->
        
        <div class="col-lg-7 col-md-7">
            <table class="table table-responsive">
            <thead>
            <tr>
            <th>
            Name
            </th>
            <th>
            Row
            </th>
            <th>
            Columns
            </th>
            <th>
            View
            </th>
            <th>
            CSV Download
            </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach( $kb->ls() as $tn){ 
             if (in_array($tn, $ignore_tables))continue;
                ?>
            <tr>
                <td>
                <?php echo $tn; ?>
                </td>
                <td>
                <?php echo $kb->row_count($tn); ?>
                </td>
                <td>
                <?php echo SmsWfUtils::flatten($kb->t_columns($tn)); ?>
                </td>
              <?php if ($kb->row_count($tn) > 0) {?>
              
                <td>
                <form action="/index.php"  method="get" style="float: left;">
                  <?php if (strpos($tn,"user_data") !== false) { ?>
                    <input type=hidden name=view value="user_report">
                  <?php } else { ?>
                    <input type=hidden name=view value="user_data_table">
                  <?php } ?>
                    <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                    <input type=hidden name=tabella value="<?php echo $tn; ?>">
                    <button class="btn btn-sim1" type="submit" name="submit" value="db">View</button>
                </form>
                </td>
                
                <td>
                <form class="form-inline" action="/views/sms/wiz/wiz_wf_bot_kb_csv.php"  method="get" style="float: left;">
                    <input type=hidden name=view value="user_report">
                    <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                    <input type=hidden name=user_id value="<?php echo $user_id; ?>">
                    <input type=hidden name=tabella value="<?php echo $tn; ?>">
                    <button class="btn btn-sim1" type="submit" name="submit" value="csv">CSV</button>
                </form>
                </td>
              <?php }  else { ?>
              	<td colspan=2">
              		<h4 class="btn-sim2">No Data</h4>
              	</td>
              <?php } ?>
            </tr>
            <?php } ?>
            </tbody>
            </table>
          <form action="/index.php"  method="get">
            <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
            <input type=hidden name=view value="<?php echo WORKFLOW_LISTING; ?>">
            <button type="submit" name="submit" value="userdata" class="btn btn-sim1"> 
            Back</button>
         </form>
        </div>
     </div>
</div> 
<?php include_once(__ROOT__ . '/views/_footer.php'); ?>
</body>
