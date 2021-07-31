<?php
include_once(__ROOT__ . '/classes/core/Log.php');


$tabella = "user_data";
$user_id = $_SESSION['user_id'];
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : null;


$row_id = isset($_GET['row_id']) ? $_GET['row_id'] : null;
$submit = isset($_GET['submit']) ? $_GET['submit'] : null;

if ($submit==null){
    $submit = isset($_POST['submit']) ? $_POST['submit'] : null;
}

if ($user_id==null || $bot_id == null){
    $_SESSION['message'] = "Session expired";
    header("Location:  /index.php", true, 307);
}

include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/wf/data/WfData.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');

$kb = new WfData($user_id, $bot_id);

$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

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
        $_SESSION['message']  = "Delete Failed". $e->getMessage();
    }
}
$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);
include_once(__ROOT__ . '/views/_header.php');
?>
<body>
<div class="container top">
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_menu.php');
     include(__ROOT__.'/views/sms/wiz/wiz_wf_body_menu.php');

    if (count($kb->ls())==0){ // NO KB?>
      <div class="row">
        <div class="col-lg-12 col-md-12 table-responsive">
        <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_kb.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>

           <h4>No Knowledge Base</h4>
           <br/>
           <p>The current catalog is not using any KB.</p>
			  <form action="/index.php" method="get">
				<input type=hidden name=view value="<?php echo WIZ_WF_KB_ADD; ?>">
				<input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
    		    <button type="submit" name="submit" value="customise_add" class="btn btn-block btn-sim1">Add KB Table</button>
			  </form>
         </div>
       </div>
       </div>
    <?php } else { ?>


<div class="row">
    <div class="col-lg-12 col-md-12 table-responsive">
        <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_kb.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>

            <p>You can make changes to Catalog data here. This data is rendered as well as helps in navigation control of your catalog.
            </p>

            <table class="table table-stripped" width="100%">
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
            Delete
            </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach( $kb->ls() as $tn){ ?>
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
                <td>
                <form action="/index.php"  method="get" style="float: left;">
                    <input type=hidden name=view value="<?php echo WIZ_WF_KB_TABLE; ?>">
                    <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                    <input type=hidden name=tabella value="<?php echo $tn; ?>">
                    <button class="btn btn-sim1" type="submit" name="submit" value="db">Edit</button>
                </form>
                </td>
                <td>
                <form action="/index.php"  method="get" style="float: left;" onsubmit="return confirm('Do you really want delete the table ? The action will invalidate any of the patterns used in message!');">
                    <input type=hidden name=view value="<?php echo WIZ_WF_KB; ?>">
                    <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                    <input type=hidden name=tabella value="<?php echo $tn; ?>">
                    <button class="btn btn-sim1" type="submit" name="submit" value="delete">Del</button>
                </form>
                </td>


            </tr>
            <?php } ?>
            <tr>
            <td colspan="5">
			  <form action="/index.php" method="get">
				<input type=hidden name=view value="<?php echo WIZ_WF_KB_ADD; ?>">
				<input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
    		    <button type="submit" name="submit" value="customise_add" class="btn btn-block btn-sim1">Add KB Table</button>
			  </form>
            </td>
            </tbody>
            </table>
       </div>
   </div>

   <?php } ?>
</div>

<?php include_once(__ROOT__ . '/views/_footer.php'); ?>
</body>
