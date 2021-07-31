<?php
require_once(__ROOT__.'/classes/Forum.php');
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/sms/SmsImages.php');

$log=$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

$user_name = $_SESSION['user_name'];

$SU = new SmsImages();
$FORUM = new Forum();
if (isset($_POST['submit'])){
    $submit = $_POST['submit'];
    error_log("submit =" . $submit);
    if ($submit == "create_topic"){
        $title=$_POST['title'];
        $comment = $_POST['comment'];
        if (strlen($title)<20 || strlen($comment) < 100){
            $_SESSION['message'] = "Title should have at least 20 characters and comment should have at least 100 characters.";
        }
        else {
            $image="";
            
            $error = false;
            if ($_FILES["fileToUpload"]["error"] == 0){
                error_log(' File upload failed for '.$_FILES["fileToUpload"]["name"] . ' with error ' . $_FILES["fileToUpload"]["error"] . ' size is ' . $_FILES["fileToUpload"]["size"]);
            
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
                if ($error){
                    $_SESSION['message']=$msg;
                }
                else {
                    $filename = $_FILES["fileToUpload"]["tmp_name"];
                    $log->debug("Upload file=".$_FILES["fileToUpload"]["name"]);
                    $ext = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
                    $res = $SU->uploadFileToSimOnline("forum/img/".$FORUM::guidv4().".".$ext, $filename);
                    $image=$res['ObjectURL'];
                }
            }
            $FORUM->saveComment($user_name, $title, $comment, -1, $image);
            
            header ( "Location: /index.php?view=forum_listing", true, 307 );
        }
    }
    include(__ROOT__.'/views/_header.php');
}


?>

<body>
<div class="container top"> 
    <?php include(__ROOT__.'/views/forum/forum_menu.php'); ?>

    <h4>Create Topic</h4>
    
   <form id="targetForm" action="/index.php?view=<?php echo FORUM_CREATE_TOPIC; ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="username" value="<?php echo $user_name; ?>">
		<div class="form-group">
			<label id="label" >Title</label><input
				class="form-control" type="text" name="title" placeholder="Title of the discussion"
				required>
		</div>
		<div class="form-group">
			<label id="label" >Comment</label><input
				class="form-control" type="text" name="comment" placeholder="Describe the issue"
				required>
		</div>
		<div class="form-group">
			<label id="label" >Upload Image</label>
       		<input  class="form-control" type="file" class="form-control-file border" name="fileToUpload" id="fileToUpload">
        </div>
		<div class="form-group">
			<button type="submit" name="submit" value="create_topic" class="btn btn-sim1">Submit</button>
		</div>
	</form>

</div>
<?php 
include(__ROOT__.'/views/_footer.php'); ?>
</body>