<?php
include(__ROOT__.'/views/_header.php');
require_once(__ROOT__.'/classes/Forum.php');
include_once(__ROOT__ . '/classes/core/Log.php');

$log=$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

$user_name = $_SESSION['user_name'];

$FORUM = new Forum();

$forum = $FORUM->getTopic($_GET['id']);
$replies = $FORUM->getReplies($_GET['id']);
?>

<body>
<div class="container top"> 
    <?php include(__ROOT__.'/views/forum/forum_menu.php'); ?>
	
    
            <div class="card-title"><h4><?php echo $forum['title'];?> </h4><h8>&emsp;&emsp;-- posted by <?php echo $forum['user_name'];?></h8></div>
            <p class="card-text"><?php echo $forum['comment'];?></p>
            <?php if ($forum['image'] != "") {?>
            <img class="img-fluid" src="<?php echo $forum['image']; ?>" height="200px" alt="Card image">
            <?php } ?>
        
          <?php foreach($replies as $reply) {  ?>
          <hr/>
          <div class="row">
            <div class="col-2">
    			<h8><?php echo $reply['user_name'];?>-></h8>
            </div>
            <div class="col-10">
                <h6><?php echo $reply['comment'];?></h6>
                <?php if ($reply['image'] != "") {?>
                <img class="img-fluid" src="<?php echo $reply['image']; ?>" width="20vw" alt="Card image">
                <?php } ?>
        		</div>
    	  </div>
    	<?php } ?>
         <div class="row img-box">
            <div class="col-2">
    		    <h8><?php echo $user_name; ?>-></h8>
            </div>
            <div class="col-10">
               <br/>
               <form id="targetForm" action="/index.php?view=<?php echo FORUM_CREATE_TOPIC; ?>" method="post" enctype="multipart/form-data">
            		<input type="hidden" name="username" value="<?php echo $user_name; ?>">
            		<input type="hidden" name="title" value="<?php echo $forum['title'];?>">
            		<div class="form-group">
            			<label id="label" style="display: none;">Reply</label><input
            				class="form-control" type="text" name="comment" placeholder="Post in your reply"
            				required>
            		</div>
            		 <div class="form-group row">
            		   <div class="col-8">
                   		<input  class="form-control" type="file" class="form-control-file border" name="fileToUpload" id="fileToUpload">
                   	   </div>
            		   <div class="col-4">
            			<button type="submit" name="submit" value="create_topic" class="btn btn-sim1">Submit</button>
            		   </div>
                    </div>
            	</form>
           </div>
    	  </div>
   

</div>
<?php 
include(__ROOT__.'/views/_footer.php'); ?>
</body>