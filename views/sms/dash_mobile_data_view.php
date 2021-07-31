<?php
include_once (__ROOT__ . '/views/_header.php');
include_once (__ROOT__ . '/classes/core/Log.php');
include_once (__ROOT__ . '/classes/wf/data/WfUserData.php');
include_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once (__ROOT__ . '/classes/core/Icons.php');
require_once (__ROOT__ . '/classes/wf/data/SmsContext.php');

$_SESSION ['log'] = new Log ("info");
$_SESSION ['message'] = "";

$user_id = $_SESSION ['user_id'];
$bot_id = $_GET ['bot_id'];
$submit = isset ( $_GET ['submit'] ) ? $_GET ['submit'] : null;

$kb = new WfUserData ( $user_id, $bot_id );

if ($submit == "delete"){
    $number = $_GET['number'];
    error_log("Number=".$number);
    $kb->deleteUserData($number);
    //delete user context after archiving
    $Ctx = new SmsContext($user_id, $bot_id);
    $Ctx->deleteContext($number);
}
else if ($submit == "archive"){
    $number = $_GET['number'];
    $kb->archiveUserData($number);
    $kb->deleteUserData($number);
    //delete user context after archiving
    $Ctx = new SmsContext($user_id, $bot_id);
    $Ctx->deleteContext($number);
}
else if ($submit == "annotate"){
    $number = $_GET['number'];
    $value = $_GET['annotate'];
    $kb->saveUserData($number, "annotate", $value);
}
else if ($submit == "clear"){
    $kb->deleteReportFormat("default");
}

list($cols, $rows) = $kb->generateReport();

$format_cols = $kb->getReportFormat("default");
if (count($format_cols) > 1){
    $cols = $format_cols;
}

if ($submit == "right"){
    $fcols = $kb->getReportFormat("default");
    error_log("Right=".print_r($fcols, true));
    if (count($fcols) > 1){
        $col = $_GET['col'];
        $cols = $kb::down($fcols, $col);
        error_log("Right Shift=".print_r($cols, true));

    }
    $kb->saveReportFormat("default", $cols);
}
else if ($submit == "left"){
    $fcols = $kb->getReportFormat("default");
    error_log("Left=".print_r($fcols, true));
    if (count($fcols) > 1){
        $col = $_GET['col'];
        $cols = $kb::up($fcols, $col);
        error_log("Left Shift=".print_r($cols, true));
    }
    $kb->saveReportFormat("default", $cols);
}

$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);
error_log($bot_id.", ".print_r($wf, true));

if ($submit == "search"){
    $search_text = $_GET['search'];
    if ($search_text == "" || $search_text == null ){
        $submit = "";
        $search_text = "";
    }
}

$Icon = new Icons ();
?>
<body>
<div class="container-fluid" style="padding-top: 100px;">
<table>
<tr>
<?php

echo "<td>";
echo '<form class="form-inline" action="/index.php"  method="get" style="float: left;"  onsubmit="return confirm(\'Do you really want clear the sorted columns ?\');">';
echo '<input type=hidden name="view" value="user_report">';
echo '<input type=hidden name="bot_id" value="'.$bot_id.'">';
echo '<td><button class="btn btn-sim3" type="submit" name="submit" value="clear">Clear</button></td>';
echo '</form>';
echo "</td>";
?>
<td>
 <form action="/index.php" method="get">
    <input type=hidden name="view" value="user_report">
    <input type="text" name="search" value="<?php echo $search_text; ?>">
    <input type=hidden name="bot_id" value="<?php echo $bot_id; ?>">
    <input type=hidden name="user_id" value="<?php echo $user_id; ?>">
    <input type=hidden name=tabella value="<?php echo $tabella; ?>">
    <button type="submit" name="submit" value="search" style="background: transparent; border: 0px;"
            data-toggle="tooltip" data-placement="right" title="Enter the user mobile number or transaction id to continue"><?php echo $Icon->get("search", "1.5", "blue"); ?>
    </button>
 </form>
</td>
</tr>
</table>
<hr/>
<table class="table table-striped" style="table-layout: fixed; width: 100%">
<thead>
<tr>
<td>Nos</td>
<?php foreach($cols as $col) {
    if ($col == "annotate")continue;
    echo "<td  style='word-wrap: break-word'>".$col."</td>";
}?>
</tr>
</thead>
<tbody>
<tr>
<?php foreach($rows as $key=>$row) {
    if ($submit == "search" && strpos($key, $search_text) === false)continue;
    echo "<tr>";
    echo "<td style='word-wrap: break-word'>".$key."</td>";
    foreach($cols as $col) {
        if ($col == "annotate")continue;
        echo "<td style='word-wrap: break-word'>". (array_key_exists($col, $row) ? $row[$col] : "") ."</td>";
    }
    echo '</tr><tr><td colspan="'.count($cols).'">';
    echo '<form class="form-inline" action="/index.php"  method="get" style="float: left;">';
    echo '<input type=hidden name="view" value="user_report">';
    echo '<input type=hidden name="bot_id" value="'.$bot_id.'">';
    echo '<input type=hidden name="number" value="'.$key.'">';
    echo '<input type=hidden name=tabella value="'.$tabella.'">';
    echo '<input type=text name="annotate" value="'.(isset($row['annotate']) ? $row['annotate'] : "").'">';
    echo '<button class="btn btn-sim3" type="submit" name="submit" value="annotate">'. $Icon->get("envelope", "1.5", "green") .'</button>';
    echo '</form>';
    echo '</td>';
    echo "</tr>";
} ?>
</tr>
</tbody>
</table>
  <form action="/index.php"  method="get">
    <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
    <input type=hidden name=view value="<?php echo WORKFLOW_LISTING; ?>">
    <button type="submit" name="submit" value="userdata" class="btn btn-sim3">
    Back</button>
 </form>
<?php include_once(__ROOT__ . '/views/_footer.php'); ?>
</body>
