<?php
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/sms/SmsImages.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');

$log=$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

$user_id = $_SESSION['user_id'];
$submit = isset($_GET['submit']) ? $_GET['submit'] : null;
$bot_id = isset($_GET['bot_id']) ? $_GET['bot_id'] : null;

$SU = new SmsImages();

$msg="";
if (isset($_POST['submit'])){
    $submit = $_POST['submit'];
    error_log("submit =" . $submit);
    if ($submit == "add"){
        $name=$_POST['name'];
        $bot_id = $_POST['bot_id'];

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

        if ($_FILES["fileToUpload"]["size"] > 2500000) {
            error_log('File is too large for '.$_FILES["fileToUpload"]["name"] . ' with error ' . $_FILES["fileToUpload"]["size"]);
            $msg=$msg." File size of less than 2MB is allowed ";
            $error = true;
        }
        if ($name == "logo"){
            $msg=$msg." The image name cannot be logo, to change logo goto Logo update.";
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
            $SU->uploadFileToSimOnline($bot_id."/img/".$name.".".$ext, $filename);
        }

    }
}

if ($submit == "delete"){
    $basename = $_GET['basename'];
    $SU->deleteImage($bot_id."/img/".$basename);
    $log->debug("Deleteing".$bot_id."/img/".$basename);
}

$Limages = $SU->listImages($bot_id);
$count = $SU->imageCount($bot_id);

$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);
include(__ROOT__.'/views/_header.php');
?>
<body>
<div class="container top">
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_menu.php'); ?>
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_body_menu.php'); ?>
        <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_images.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
            <h4>Manage Media for <?php echo $wf['name']; ?></h4>
            <p>This is your image store. Upload high-quality png, jpeg, or jpg images of your products/items.
            You can always return here to make changes or to add more images. Images should be less than 500k, ideally of around 400x400 pixels.
            </p>
                <?php if ($count > 100) {
                    echo "<h9> You have exceeded the quota of 100 images, delete some to uplaod</h9>";
                } else { ?>
                  <form class="form-control form-inline" action="/index.php?view=<?php echo WIZ_WF_IMAGES; ?>"  method="post" enctype="multipart/form-data">
                      <label for="desc">Image Name:</label>
                      <input id="textInput" class="form-control" type="text" class="form-control" id="name" placeholder="Enter Image Name" name="name" required>
                      <div class="valid-feedback">Valid.</div>
                      <div class="invalid-feedback">Please fill out this field.</div>

                    <label for="sel1">Upload:</label>
                    <input  class="form-control" type="file" class="form-control-file border" name="fileToUpload" id="fileToUpload" required>
                    <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">

                    <button type="submit" name="submit" value="add" class="btn btn-sim1 btn-block">Add New Image</button>
                  </form>
                <br/>
                <?php } ?>
                <br/>
                <h3> <?php echo $count - 1; ?> images available</h3>
                <div class="row">
                <?php foreach ($Limages as $img) {
                    if (strpos(basename($img), "logo") !== false) continue;
                    ?>
                    <div class="col">
                        <img width="100px" src="<?php echo $img."?rand=".rand(); ?>">
                         <p id="<?php echo str_replace(".", "", basename($img)); ?>"><h6><?php echo basename($img); ?></h6></p>
                        <button onclick="copyToClipboard('<?php echo $img; ?>')" class="btn btn-sim1">
                                    <i class="ti-layers"></i></button>
                        <form action="/index.php"  method="get" style="float: left;" onsubmit="return confirm('Do you want delete this Image ?');">
                        <input type=hidden name=view value="<?php echo WIZ_WF_IMAGES; ?>">
                        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                        <input type=hidden name=basename value="<?php echo basename($img); ?>">
                        <button type="submit" name="submit" value="delete" class="btn btn-sim2">
                                    <i class="ti-trash"></i></button>
                        </form>
                    </div>
                <?php } ?>
                </div>
                <br/><br/><br/>
</div>
<script>
function copyToClipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
  }
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
</script>

<?php
include(__ROOT__.'/views/_footer.php');
?>
</body>