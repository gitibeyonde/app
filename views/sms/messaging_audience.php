<?php
define('__SMSMROOT__', dirname(dirname(dirname(__FILE__))));
include(__ROOT__.'/views/_header.php');
include_once(__ROOT__ . '/classes/wf/data/WfAudience.php');
include_once(__ROOT__ . '/classes/core/Log.php');

$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

$user_id = $_SESSION['user_id'];
$submit = isset($_GET['submit']) ? $_GET['submit'] : null;
$tabella = isset($_GET['tabella']) ? $_GET['tabella'] : null;

$ad = new WfAudience($user_id);


error_log("post name submit =" . $submit);
if (isset($_POST['submit'])){
    $submit = $_POST['submit'];
    error_log("Template submit =" . $submit);
    if ($submit == "add"){
        $name=$_POST['name'];
        $filename = $_FILES["fileToUpload"]["tmp_name"];
        try {
            $ad->loadWFAudience($name, $filename);
        }
        catch (Exception $e){
            $_SESSION['message']  = "Upload Failed". $e->getMessage();
        }
    }
    else if ($submit == "delete"){
        try {
            $tabella=$_POST['tabella'];
            $ad->deleteWFAudience($tabella);
        }
        catch (Exception $e){
            $_SESSION['message']  = "Upload Failed". $e->getMessage();
        }
        
    }
}


error_log("User id=".$user_id);
?>
<body>
<div class="container top"> 
<?php include(__ROOT__.'/views/sms/messaging_menu.php'); ?>
     <br/>
            <br/>
            <h4>Upload Audience File</h4>
            <br/>
            <hr/>        

  <p>Ensure there is a column named "number" with mobile numbers in international format OR a column named "email" with email addresses.</p>
  <br/>
  <form action="/index.php?view=<?php echo SMS_AUDIENCE; ?>"  method="post" enctype="multipart/form-data">
  <div class="form-group row flex-v-center">
    <div class="col-xs-3 col-sm-2">
      <label for="desc">Audience Name:</label>
     </div>
    <div class="col-xs-3">
      <input type="text" class="form-control" id="name" placeholder="Enter Audience Name" name="name" required>
      <div class="valid-feedback">Valid.</div>
      <div class="invalid-feedback">Please fill out this field.</div>
    </div>
    </div>
    
  <div class="form-group row flex-v-center">
    <div class="col-xs-3 col-sm-2">
    <label for="sel1">Upload CSV File:</label>
   </div>
    <div class="col-xs-3">
     <input type="file" class="form-control-file border" name="fileToUpload" id="fileToUpload">
   </div>
   </div>
    <button type="submit" name="submit" value="add" class="btn btn-sim1">Add</button>
  </form> 
         
<br/>
<hr/>        
<br/>
         
<h3>Audience Listing</h3>        

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
Remove
</th>
</tr>
</thead>
<tbody>
<?php foreach( $ad->ls() as $tn){ ?>
<tr>
    <td>
    <?php echo $tn; ?>
    </td>
    <td>
    <?php echo $ad->row_count($tn); ?>
    </td>
    <td>
    <?php echo SmsWfUtils::flatten($ad->t_columns($tn)); ?>
    </td>
    <td>
    <form action="/index.php"  method="get" style="float: left;">
        <input type=hidden name=view value="audience_db_table">
        <input type=hidden name=tabella value="<?php echo $tn; ?>">
        <button class="btn btn-sim1" type="submit" name="submit" value="db">View</button>
    </form>
    </td>
    <td>
    <form action="/index.php"  method="post" style="float: left;"  onsubmit="return confirm('Do you really want delete the table ?');">
        <input type=hidden name=view value="sms_audience">
        <input type=hidden name=tabella value="<?php echo $tn; ?>">
        <button class="btn btn-sim1" type="submit" name="submit" value="delete">Delete</button>
    </form>
    </td>
</tr>
<?php } ?>
</tbody>
</table>


           
        <div class="row">
            <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br />
        </div>

</div>

    <?php include(__SMSMROOT__.'/views/_footer.php'); ?>
	</main>
</body>
