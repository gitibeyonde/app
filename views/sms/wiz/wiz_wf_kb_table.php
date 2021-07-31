<?php
include_once(__ROOT__ . '/views/_header.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/wf/data/WfData.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
include_once(__ROOT__ . '/classes/sms/SmsImages.php');

$_SESSION['log'] = new Log ("info");

$_SESSION['message'] = "";
$user_id = $_SESSION['user_id'];
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : null;

$kb = new WfData($user_id, $bot_id);

$tabella = isset($_GET['tabella']) ? $_GET['tabella'] : null;
$row_id = isset($_GET['row_id']) ? $_GET['row_id'] : null;
$submit = isset($_GET['submit']) ? $_GET['submit'] : null;

if($submit == "saverow"){
    $sql = "update ".$tabella." set ";
    $headers = $kb->t_columns($tabella);
    foreach($headers as $head){
        $sql.= $head."='".$_GET[$head]."', ";
    }
    $sql = substr($sql, 0, strlen($sql)-2);
    $sql .= " where rowid = ".$row_id;
    error_log( $sql);
    $kb->t_crtinsupd($sql);
}
else if ($submit == "delrow"){
    $sql = "delete from ".$tabella." where rowid=".$row_id;
    error_log( $sql);
    $kb->t_crtinsupd($sql);
}
else if ($submit == "addrow"){
    $sql = "insert into ".$tabella." values (";
    $headers = $kb->t_columns($tabella);
    error_log(print_r($headers, true));
    foreach($headers as $head){
        $sql.= "'".$_GET[$head]."', ";
    }
    $sql = substr($sql, 0, strlen($sql)-2);
    $sql .= ")";
    error_log( $sql);
    $kb->t_crtinsupd($sql);
}

$SU = new SmsImages();
$Limages = $SU->listImages($bot_id);

$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);
include(__ROOT__.'/views/_header.php');
?>
<body>
<div class="container top">
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_menu.php'); ?>
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_body_menu.php'); ?>
<h4>KB <?php echo $tabella; ?></h4>
<br/>
<table class="table table-striped">
<thead>
<tr>
<th>
<h4>row</h4>
</th>
<?php
$headers = $kb->t_columns_types($tabella);
foreach($headers as $head=>$type){
    echo "<th><h5>".$head.":".$type."</h5></th>";
}
?>
<th>
<h4>edit</h4>
</th>
<th>
<h4>del</h4>
</th>
</tr>
</thead>
<tbody>
<?php
$rows = $kb->t_data($tabella);
foreach($rows as $row){

    echo "<tr>";
    if ($submit == "editrow" && $row_id == $row['rowid']){
        echo '<form action="/index.php"  method="get" style="float: left;">';
        foreach($row as $key=>$val){
            if ($key == "rowid"){
                echo "<td>".$val."</td>";
                echo '<input type=hidden name="row_id" value="'.$val.'">';
            }
            else if (strpos($key, "img") !== false) {
                echo "<td>";
                echo "<select name=".$key.">";
                foreach ($Limages as $img) {
                    if ($img == $val){
                        echo "<option value='".$img."' selected>".basename($img)."</option>";
                    }
                    else {
                        echo "<option value='".$img."'>".basename($img)."</option>";
                    }
                }
                echo "</select>";
                echo "</td>";
            }
            else {
                echo "<td>";
                echo '<input type=text name='.$key.' value="'.$val.'" style="width: -webkit-fill-available;">';
                echo "</td>";
            }
        }
        echo "<td>";
        echo '<input type=hidden name=view value="wiz_wf_kb_table">';
        echo '<input type=hidden name=bot_id value="'.$bot_id.'">';
        echo '<input type=hidden name=tabella value="'.$tabella.'">';
        echo '<input type=hidden name=row_id value="'.$row['rowid'].'">';
        echo '<button class="btn btn-sim1" type="submit" name="submit" value="saverow">Save</button>';
        echo '</form>';
        echo "</td><td></td>";
    }
    else {
    foreach($row as $key=>$val){
        if (strpos($key, "img") !== false) {
            echo "<td><img src='".$val."' width='50px'></td>";
        }
        else {
            echo "<td>".htmlspecialchars($val)."</td>";
        }
    }
    echo "<td>";
    echo '<form action="/index.php"  method="get" style="float: left;">';
    echo '<input type=hidden name=view value="wiz_wf_kb_table">';
    echo '<input type=hidden name=bot_id value="'.$bot_id.'">';
    echo '<input type=hidden name=tabella value="'.$tabella.'">';
    echo '<input type=hidden name=row_id value="'.$row['rowid'].'">';
    echo '<button class="btn btn-sim1" type="submit" name="submit" value="editrow" style="">Edit</button>';
    echo '</form>';
    echo "</td>";
    echo "<td>";
    echo '<form action="/index.php"  method="get" style="float: left;">';
    echo '<input type=hidden name=view value="wiz_wf_kb_table">';
    echo '<input type=hidden name=bot_id value="'.$bot_id.'">';
    echo '<input type=hidden name=tabella value="'.$tabella.'">';
    echo '<input type=hidden name=row_id value="'.$row['rowid'].'">';
    echo '<button class="btn btn-sim1" type="submit" name="submit" value="delrow"">Del</button>';
    echo '</form>';
    echo "</td>";
    }
    echo "</tr>";
}

echo "<tr><td></td>";
echo '<form action="/index.php"  method="get" style="float: left;">';
echo '<input type=hidden name=view value="wiz_wf_kb_table">';
echo '<input type=hidden name=bot_id value="'.$bot_id.'">';
echo '<input type=hidden name=tabella value="'.$tabella.'">';
$headers = $kb->t_columns($tabella);
foreach($headers as $head){
    if (strpos($head, "img") !== false || strpos($head, "image") !== false) {
        echo "<td>";
        echo "<select name=".$head.">";
        foreach ($Limages as $img) {
            if ($img == $val){
                echo "<option value='".$img."' selected>".basename($img)."</option>";
            }
            else {
                echo "<option value='".$img."'>".basename($img)."</option>";
            }
        }
        echo "</select>";
        echo "</td>";
    }
    else {
        echo '<td><input type=text name='.$head.' value=""></td>';
    }
}
echo "<td>";
echo '<button class="btn btn-sim1" type="submit" name="submit" value="addrow">Add</button>';
echo '</form>';
echo "</td>";
echo "</tr>";
?>
</tbody>
</table>
			<form class="form-control" action="/index.php" method="get" width="100%">
				<input type=hidden name=view value="<?php echo WIZ_WF_KB; ?>">
				<input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
    		  <button type="submit" name="submit" value="customise_add" class="btn btn-sim1 btn-block">Back</button>
			</form>
<br/> <br/>
<?php if ($wf['category'] != "basic") { ?>
<p>If the KB contains images then you can select from the dropdown. The dropdown will be available if your the column name has "img" in it.
In case you want to add images and drop down is not available on the column then you copy the image URL from Images and paste it in the desired column.
</p>
<?php } ?>
</div>
<script>
$("input[type=text]").on('input', function() {
  var c = this.selectionStart,
      r = /[^a-z0-9-_ ]/gi,
      v = $(this).val();
  if(r.test(v)) {
    $(this).val(v.replace(r, ''));
    c--;
  }
  this.setSelectionRange(c, c);
});
</script>
<?php include_once(__ROOT__ . '/views/_footer.php'); ?>
</body>
