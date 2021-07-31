<?php
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/sms/SmsImages.php');
require_once(__ROOT__.'/classes/sms/SmsPayment.php');
include_once(__ROOT__ . '/classes/sms/SmsMinify.php');

$log = $_SESSION['log'] = new Log ("info");
$user_id = $_SESSION['user_id'];

$submit = isset($_GET["submit"]) ? $_GET["submit"] : $_POST['submit'];
$bot_id = isset($_GET["bot_id"]) ? $_GET["bot_id"] : $_POST['bot_id'];

$SI = new SmsImages();
$WFDB = new WfMasterDb();

if ($submit == "change_desc"){
    $name = $_GET['name'];
    $category = $_GET['category'];
    $description = $_GET['description'];
    $format = $_GET['format'];
    error_log("Format=".$format);
    $WFDB->updateWorkflow($user_id, $bot_id, $name, $category, $description, $format);
}
else if ($submit == "add") {
    $user_id = $_SESSION['user_id'];
    //check if the bot exists for this user id
    $wf = $WFDB->getWorkflowForUser($user_id, $bot_id);

    if ($wf == null){
        $from_bot_id = $bot_id;
        if ($from_bot_id == null ){
            $_SESSION("Bad data unknown catalog id");
        }

        if ($from_bot_id == null){
            error_log("FATAL: Matching template not found");
            $_SESSION["message"] = "FATAL: Matching template not found";
        }

        $from_user_id = 1;  //CREATOR OF THE TEMPLATE
        $wf = $WFDB->getWorkflow($from_bot_id);
        $owf = $WFDB->getWorkflowWithName( $user_id, $wf['name']."-".substr(time()."", 2, 5));
        if ($owf != false){
            error_log("The workflow being copied already exists ".$owf["bot_id"]);
            $bot_id = $owf["bot_id"];
        }
        else {
            // move the nodes to this workflow with renamed states
            $bot_id = $WFDB->copyWorkflow($from_bot_id, $user_id);
            if ($bot_id != null) {
                // copy the BOT KB to this new Bot's KB
                WfDb::copyBotKB($from_user_id, $from_bot_id, $user_id, $bot_id);
                // copy images
                $Im = new SmsImages();
                $Im->copyImages($from_bot_id, $bot_id);
            } else {
                error_log("FATAL: catalog app creation failed");
                $_SESSION["message"] = "FATAL: catalog app creation failed";
            }
        }
    }
}
else if ($submit == "add-logo"){
        $SU = new SmsImages();
        $name = $_POST['name'];
        $SU->deleteImage($bot_id."/img/".$name.".png");
        $SU->deleteImage($bot_id."/img/".$name.".jpg");
        $SU->deleteImage($bot_id."/img/".$name.".jpeg");
        $log->debug("Deleting".$bot_id."/img/".$name);

        if (isset($_POST['img_val'])){
            $img_val = $_POST['img_val'];
            $imageData = base64_decode(end(explode(",", $img_val)));
            $SU->uploadObjectToSimOnline($bot_id."/img/logo.png", $imageData);
        }
        else {
            $error = false;
            if ($_FILES["fileToUpload"]["error"] != 0){
                error_log(' File upload failed for '.$_FILES["fileToUpload"]["name"] . ' with error ' . $_FILES["fileToUpload"]["error"] . ' size is ' . $_FILES["fileToUpload"]["size"]);
                $msg=" Unknown error ";
                $error = true;
            }

            if ($_FILES["fileToUpload"]["type"] != 'image/jpeg' && $_FILES["fileToUpload"]["type"] != 'image/png' && $_FILES["fileToUpload"]["type"] != 'image/jpg' ){
                error_log(' - bad format for '.$_FILES["fileToUpload"]["name"] . ' with error ' .$_FILES["fileToUpload"]["type"]);
                $msg=$msg." Illegal Format, Use jpg/jpeg/png ";
                $error = true;
            }

            if ($_FILES["fileToUpload"]["size"] > 500000) {
                error_log('File is too large for '.$_FILES["fileToUpload"]["name"] . ' with error ' . $_FILES["fileToUpload"]["size"]);
                $msg=$msg." File size of less than 500k is allowed ";
                $error = true;
            }
            if ($bot_id == null){
                $msg=$msg." Authorization error ";
                $error = true;
            }
            if ($error){
                $_SESSION['message']=$msg;
            }
            else {
                $filename = $_FILES["fileToUpload"]["tmp_name"];
                $log->debug("Upload file=".$_FILES["fileToUpload"]["name"]);
                $ext = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
                $SU->uploadFileToSimOnline($bot_id."/img/logo.".$ext, $filename);
            }
        }
}
$wf = $WFDB->getWorkflow($bot_id);
include (__ROOT__ . '/views/_header.php');



$SI = new SmsImages();
$logo = $SI->logo($bot_id);

?>
<body>
<div class="container top">

  <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_menu.php'); ?>

  <br/>
  <div class="card sel0">
       <div class="row">
             <div class="col-4"><img name="title-image" class="img-fluid" style="max-height: 100px;" src="<?php echo $logo."?".rand(); ?>">
             </div>
             <div class="col-7 my-auto" style="text-align: center;padding-top: 20px;"><h1 name="h1-title"><?php echo $wf['name']; ?></h1>
             </div>
      </div>
      <hr/>
      <div class="row">
             <div class="col-12 my-auto" style="text-align: center;"><h6><?php echo $wf['description']; ?></h6>
             </div>
      </div>
  </div>

 <br/>  <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_header.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>


    <h4>Modify Title and Description</h4>
    <div class="card">
      <div class="container">
		<form class="form-inline" action="/index.php" method="get" width="100%">
			<input type=hidden name=view value="<?php echo WIZ_WF_DESC; ?>">
			<input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
			<input type=hidden name=category value="<?php echo $wf['category']; ?>">

			<label>Title</label>
			<input id="textInput" class="form-control" name="name" placeholder="Catalog Title" value="<?php echo $wf['name']; ?>" required>

			<label>Description:</label>
			<textarea id="textAreaInput" class="form-control" name="description" placeholder="App description goes here" style="width: 40%;" required><?php echo $wf['description']; ?></textarea>

			<input type=hidden name=format value="4">
			<button type="submit" name="submit" value="change_desc" class="btn btn-block btn-sim4">Save Title and Description</button>
		</form>
       </div>
   </div>

  <br/>
  <h4>Modify Logo</h4>

  <div class="card">
    <ul class="nav nav-tabs">
      <li  class="active" style="padding-right: 20px;"><a data-toggle="tab" href="#create"  class="btn btn-block btn-sim5">Create Logo Online</a></li>
      <li><a data-toggle="tab" href="#upload" class="btn btn-block btn-sim5">Upload Logo</a></li>
    </ul>
   <br/>
    <div class="tab-content">
      <div id="upload" class="tab-pane fade in active">
             <form class="form-control form-inline" action="/index.php?view=wiz_wf_desc&bot_id=<?php echo $bot_id; ?>"  method="post" enctype="multipart/form-data">
                  <input type="hidden" class="form-control" name="name" value="logo">
                  <input type=hidden name=bot_id value="<?php echo $wf['bot_id']; ?>">
                  <label><h4>Upload Logo (png, jpeg, jpg):</h4></label>
                  <input type="file" class="form-control-file border" name="fileToUpload" id="fileToUpload" required>
                  <button type="submit" name="submit" value="add-logo" class="btn btn-sim4 btn-block">Upload Logo Image</button>
              </form>
      </div>

      <div id="create" class="tab-pane fade">
        <div class="row">
          <div class="col-12">
                <?php include(__ROOT__.'/views/logo/create_logo.php'); ?>
           </div>
        </div>
      </div>
  </div>
  </div>

</div>
<script>
$('.nav-tabs a[href="#create"]').tab('show')

$('#textInput').on('input', function() {
	  var c = this.selectionStart,
	      r = /[^a-z0-9_\- ]/gi,
	      v = $(this).val();
	  if(r.test(v)) {
	    $(this).val(v.replace(r, ''));
	    c--;
	  }
	  this.setSelectionRange(c, c);
	});
$('#textAreaInput').on('change keyup paste', function() {
	  var c = this.selectionStart,
	      r = /[^a-z0-9_\-\. ]/gi,
	      v = $(this).val();
	  if(r.test(v)) {
	    $(this).val(v.replace(r, ''));
	    c--;
	  }
	  this.setSelectionRange(c, c);
	});
</script>

<?php
include (__ROOT__ . '/views/_footer.php');
?>
</body>
