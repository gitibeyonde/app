<?php
include_once(__ROOT__ . '/views/_header.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/wf/data/WfData.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');

$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

$user_id = $_SESSION['user_id'];
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : null; // is required by the wiz_wf_emnu
$row_id = isset($_GET['row_id']) ? $_GET['row_id'] : null;
$submit = isset($_GET['submit']) ? $_GET['submit'] : null;
$count = isset($_GET['count']) ? $_GET['count'] : 0;
$tabella = isset($_GET['tabella']) ? $_GET['tabella'] : null;

$col = array();
$type= array();
if ($submit == "addtable"){
    if ($count == 0) {
        $_SESSION['message']  = "Table creation requires at least one column";
    }
    else {
        $kb = new WfData($user_id, $bot_id);
        for ($i=0;$i<$count; $i++){
            array_push($col, $_GET["col".$i]);
            array_push($type, $_GET["type".$i]);
        }
        $kb->createTable($tabella, $col, $type);
        echo '<meta http-equiv="refresh" content="0; URL=/index.php?view=wiz_wf_kb&bot_id=' .$bot_id.'" />';
        return;
    }
}
else  if ($submit == "addcolumn"){
    $count = $count +1;
    for ($i=0;$i<$count; $i++){
        array_push($col, $_GET["col".$i]);
        array_push($type, $_GET["type".$i]);
    }
}
else  if ($submit == "removecolumn"){
    $count = $count -1;
    for ($i=0;$i<$count; $i++){
        array_push($col, $_GET["col".$i]);
        array_push($type, $_GET["type".$i]);
    }
}
$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);
include(__ROOT__.'/views/_header.php');
?>

<body>
<div class="container top">
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_menu.php');  // $bot_id required ?>
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_body_menu.php'); ?>

  	<form class="form-control" action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo WIZ_WF_KB_ADD; ?>">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=user_id value="<?php echo $user_id; ?>">

        <div class="form-group">
        	<input type=text name=tabella value="<?php echo $tabella?>" Placeholder="Table Name" required>
       	</div>

        <?php for ($i=0;$i<$count; $i++) {
            echo '<div class="form-group">';
            echo '<input type=text name=col'.$i.' value="'.$col[$i].'">';
            echo '<input type=text name=type'.$i.' value="'.$type[$i].'">';
            if ($i == $count -1){
                echo '<button class="btn btn-sim3" type="submit" name="submit" value="removecolumn">X</button>';
            }
            echo '</div>';
         } ?>

         <div class="form-group">
            <input type=text name="col<?php echo $count; ?>" value="" Placeholder="Column Name">
            <select id="type" name="type<?php echo $count; ?>"'.$i.'>
            <option value="TEXT">TEXT</option>
            <option value="INTEGER">INTEGER</option>
            <option value="REAL">REAL</option>
            </select>
            <input type=hidden name=count value="<?php echo $count; ?>">
        	<button class="btn btn-sim3" type="submit" name="submit" value="addcolumn">+</button>
        </div>

        <div class="form-group">
        	<button class="btn btn-sim1" type="submit" name="submit" value="addtable">Create</button>
        </div>
     </form>


            <form class="form-control" action="/index.php" method="get" width="100%">
                <input type=hidden name=view value="<?php echo WIZ_WF_KB; ?>">
                <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
              <button type="submit" name="submit" value="customise_add" class="btn btn-sim2 btn-block">Cancel</button>
            </form>

    <p>
    If a field should contain images form Images, then name it like imgPrefix or Suffixing.
    </p>
</div>
<?php include_once(__ROOT__ . '/views/_footer.php'); ?>
</body>
