<?php
include_once(__ROOT__ . '/views/_header.php');
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/wf/data/WfAudience.php');

$_SESSION['log'] = new Log ("info");

$_SESSION['message'] = "";
$user_id = $_SESSION['user_id'];

$ad = new WfAudience($user_id);

$tabella = isset($_GET['tabella']) ? $_GET['tabella'] : null;
$row_id = isset($_GET['row_id']) ? $_GET['row_id'] : null;
$submit = isset($_GET['submit']) ? $_GET['submit'] : null;

if($submit == "saverow"){
    $sql = "update ".$tabella." set ";
    $headers = $ad->t_columns($tabella);
    foreach($headers as $head){
        $sql.= $head."='".$_GET[$head]."', ";
    }
    $sql = substr($sql, 0, strlen($sql)-2);
    $sql .= " where rowid = ".$row_id;
    error_log( $sql);
    $ad->t_crtinsupd($sql);
}
else if ($submit == "delrow"){
    $sql = "delete from ".$tabella." where rowid=".$row_id;
    error_log( $sql);
    $ad->t_crtinsupd($sql);
}
else if ($submit == "addrow"){
    $sql = "insert into ".$tabella." values (";
    $headers = $ad->t_columns($tabella);
    foreach($headers as $head){
        $sql.= "'".$_GET[$head]."', ";
    }
    $sql = substr($sql, 0, strlen($sql)-2);
    $sql .= ")";
    error_log( $sql);
    $ad->t_crtinsupd($sql);
}

?>


<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/messaging_menu.php'); ?>

<form action="/index.php"  method="get" style="float: left;">
<input type=hidden name=view value="sms_audience">
<button class="btn btn-link" type="submit" name="submit" value="db" class="btn btn-sim1">Back</button>
</form>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  <br/>
  <h2> <?php echo $tabella; ?></h2>
  <br/>
<hr/> 

<table class="table table-striped">
<thead>
<tr>
<th>
<h4>row</h4>
</th>
<?php 
$headers = $ad->t_columns($tabella); 
foreach($headers as $head){ 
    echo "<th><h4>".$head."</h4></th>";
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
$rows = $ad->t_data($tabella);
foreach($rows as $row){
    
    echo "<tr>";
    if ($submit == "editrow" && $row_id == $row['rowid']){
        echo '<form action="/index.php"  method="get" style="float: left;">';
        foreach($row as $key=>$val){
            if ($key == "rowid"){
                echo "<td>".$val."</td>";
                echo '<input type=hidden name="row_id" value="'.$val.'">';
            }
            else {
                echo "<td>";
                echo '<input type=text name='.$key.' value="'.$val.'">';
                echo "</td>";
            }
        }
        echo "<td>";
        echo '<input type=hidden name=view value="audience_db_table">';
        echo '<input type=hidden name=tabella value="'.$tabella.'">';
        echo '<input type=hidden name=row_id value="'.$row['rowid'].'">';
        echo '<button class="btn btn-sim1" type="submit" name="submit" value="saverow">Save</button>';
        echo '</form>';
        echo "</td><td></td>";
    }
    else {
    foreach($row as $val){
       echo "<td>".$val."</td>"; 
    }
    echo "<td>";
    echo '<form action="/index.php"  method="get" style="float: left;">';
    echo '<input type=hidden name=view value="audience_db_table">';
    echo '<input type=hidden name=tabella value="'.$tabella.'">';
    echo '<input type=hidden name=row_id value="'.$row['rowid'].'">';
    echo '<button class="btn btn-sim1" type="submit" name="submit" value="editrow">Edit</button>';
    echo '</form>';
    echo "</td>";
    echo "<td>";
    echo '<form action="/index.php"  method="get" style="float: left;">';
    echo '<input type=hidden name=view value="audience_db_table">';
    echo '<input type=hidden name=tabella value="'.$tabella.'">';
    echo '<input type=hidden name=row_id value="'.$row['rowid'].'">';
    echo '<button class="btn btn-sim1" type="submit" name="submit" value="delrow">Delete</button>';
    echo '</form>';
    echo "</td>";
    }
    echo "</tr>"; 
}

echo "<tr><td></td>";
echo '<form action="/index.php"  method="get" style="float: left;">';
echo '<input type=hidden name=view value="audience_db_table">';
echo '<input type=hidden name=tabella value="'.$tabella.'">';
$headers = $ad->t_columns($tabella);
foreach($headers as $head){
    echo '<td><input type=text name='.$head.' value=""></td>';
}
echo "<td>";
echo '<button class="btn btn-sim1" type="submit" name="submit" value="addrow">Add</button>';
echo '</form>';
echo "</td>";
echo "</tr>";
?>
</tbody>
</table>

</div> 

</main>
<?php include_once(__ROOT__ . '/views/_footer.php'); ?>
</body>
