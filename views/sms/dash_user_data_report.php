<?php
include_once(__ROOT__ . '/views/_header.php');
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/core/Icons.php');
include_once(__ROOT__ . '/classes/wf/data/WfUserData.php');
include_once(__ROOT__ . '/classes/wf/data/WfUserDataArchive.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once (__ROOT__ . '/classes/sms/SmsMinify.php');
require_once (__ROOT__ . '/classes/wf/data/SmsContext.php');

$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

$user_id = $_SESSION['user_id'];
$bot_id = $_GET['bot_id'];
$submit =  isset($_GET['submit']) ? $_GET['submit'] : null;
$tabella = $_GET['tabella'];

if ($tabella == "user_data_archive"){
    $kb = new WfUserDataArchive($user_id, $bot_id);
}
else {
    $kb = new WfUserData($user_id, $bot_id);
}

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

$min = new SmsMinify ();
$url_otp = $min->createOwnerUrl ( $user_id, $bot_id, $_SESSION ['user_phone'], $_SESSION ['user_email'] );
?>
<body>
<div class="container-fluid" style="padding-top: 100px;">
<?php include(__ROOT__.'/views/sms/dash_menu_top.php'); ?>

<br/>
<br/>
</div>
 
<div class="container-fluid">
<div class="row">
  <table width="100%">
   <tr>
   <td>
    <form class="form-inline" action="/views/sms/dash_wf_user_data_csv.php"  method="get" style="float: left;">
        <input type=hidden name="view" value="user_report">
        <input type=hidden name="bot_id" value="<?php echo $bot_id; ?>">
        <input type=hidden name="bot_name" value="<?php echo $wf['name']; ?>">
        <input type=hidden name="user_id" value="<?php echo $user_id; ?>">
    	    <input type=hidden name=tabella value="<?php echo $tabella; ?>">
        <label><h3>User Data Report&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3></label>
        <button class="btn btn-sim1" type="submit" name="submit" value="csv" class="btn btn-sim1">(dwonload CSV)</button>
    </form>
    </td>
    <td>
     Data Scanner:<a href="https://<?php echo $url_otp; ?>" target="_blank"><?php echo $url_otp; ?></a>
    </td>
   </tr>
  </table>
</div>
<hr/>
<table> 
<tr>  
<?php 
$count=0;
foreach($cols as $col) { 
    if ($col == "annotate")continue;
    echo "<td>";
    echo '<form class="form-inline" action="/index.php"  method="get" style="float: left;">';
    echo '<input type=hidden name="view" value="user_report">';
    echo '<input type=hidden name="bot_id" value="'.$bot_id.'">';
    echo '<input type=hidden name="col" value="'.$count.'">';
    echo '<td><button class="btn btn-sim1" type="submit" name="submit" value="left" class="btn btn-sim1"><</button></td>';
    echo '</form>';
    echo "</td>";
    
    echo "<td>";
    echo $col;
    echo "</td>";
    
    echo "<td>";
    echo '<form class="form-inline" action="/index.php"  method="get" style="float: left;">';
    echo '<input type=hidden name="view" value="user_report">';
    echo '<input type=hidden name="bot_id" value="'.$bot_id.'">';
    echo '<input type=hidden name="col" value="'.$count.'">';
    echo '<td><button class="btn btn-sim1" type="submit" name="submit" value="right" class="btn btn-sim1">></button></td>';
    echo '</form>';
    echo "</td>";
    
    $count=$count+1;
}

echo "<td>";
echo '<form class="form-inline" action="/index.php"  method="get" style="float: left;"  onsubmit="return confirm(\'Do you really want clear the sorted columns ?\');">';
echo '<input type=hidden name="view" value="user_report">';
echo '<input type=hidden name="bot_id" value="'.$bot_id.'">';
echo '<td><button class="btn btn-sim1" type="submit" name="submit" value="clear" class="btn btn-sim1">Clear</button></td>';
echo '</form>';
echo "</td>";
?>
<td style="padding-left: 10px;">
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
<table class="table table-striped">
<thead>
<tr>
<td>Number</td>
<?php foreach($cols as $col) { 
    if ($col == "annotate")continue;
    echo "<td>".$col."</td>";
}?>
</tr>
</thead>
<tbody>
<tr>
<?php foreach($rows as $key=>$row) { 
    if ($submit == "search" && strpos($key, $search_text) === false)continue;
    echo "<tr>";
    echo "<td>".$key."</td>";
    foreach($cols as $col) {
        if ($col == "annotate")continue;
        echo "<td>". (array_key_exists($col, $row) ? $row[$col] : "") ."</td>";
    }
    echo '<td>';
    echo '<form class="form-inline" action="/index.php"  method="get" style="float: left;">';
    echo '<input type=hidden name="view" value="user_report">';
    echo '<input type=hidden name="bot_id" value="'.$bot_id.'">';
    echo '<input type=hidden name="number" value="'.$key.'">';
    echo '<input type=hidden name=tabella value="'.$tabella.'">';
    echo '<input type=text name="annotate" value="'.(isset($row['annotate']) ? $row['annotate'] : "").'">';
    echo '<td><button class="btn btn-sim1" type="submit" name="submit" value="annotate" class="btn btn-sim1">'. $Icon->get("envelope", "1.5", "green") .'</button></td>';
    echo '</form>';
    echo '</td>';
    
    echo '<td>';
    echo '<form action="/index.php"  method="get" style="float: left;">';
    echo '<input type=hidden name="view" value="user_data_table">';
    echo '<input type=hidden name="bot_id" value="'.$bot_id.'">';
    echo '<input type=hidden name="number" value="'.$key.'">';
    echo '<input type=hidden name="rowid" value="-1">';
    echo '<input type=hidden name=tabella value="'.$tabella.'">';
    echo '<button class="btn btn-sim1" type="submit" name="submit" value="editrow">'. $Icon->get("pen", "1.5", "orange") .'</button>';
    echo '</form>';
    echo '</td>';
    
    echo '<td>';
    echo '<form action="/index.php"  method="get" style="float: left;"  onsubmit="return confirm(\'Do you really want delete the number ?\');">';
    echo '<input type=hidden name="view" value="user_report">';
    echo '<input type=hidden name="bot_id" value="'.$bot_id.'">';
    echo '<input type=hidden name="number" value="'.$key.'">';
    echo '<input type=hidden name=tabella value="'.$tabella.'">';
    echo '<button class="btn btn-sim2" type="submit" name="submit" value="delete">'. $Icon->get("trash_can", "1.5", "red") .'</button>';
    echo '</form>';
    echo '</td>';
    
    if ($tabella == "user_data"){
        echo '<td>';
        echo '<form action="/index.php"  method="get" style="float: left;">';
        echo '<input type=hidden name="view" value="user_report">';
        echo '<input type=hidden name="bot_id" value="'.$bot_id.'">';
        echo '<input type=hidden name="number" value="'.$key.'">';
        echo '<input type=hidden name=tabella value="'.$tabella.'">';
        echo '<button class="btn btn-sim2" type="submit" name="submit" value="archive">Archive</button>';
        echo '</form>';
        echo '</td>';
    }
    
    echo "</tr>";
} ?>
</tr>
</tbody>
</table>

  <form action="/index.php"  method="get">
    <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
    <input type=hidden name=view value="<?php echo USER_DATA; ?>">
    <button type="submit" name="submit" value="userdata" class="btn btn-sim1"> 
    Back</button>
 </form>
<?php include_once(__ROOT__ . '/views/_footer.php'); ?>
</body>
