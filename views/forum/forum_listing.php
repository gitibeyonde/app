<?php
include(__ROOT__.'/views/_header.php');
require_once(__ROOT__.'/classes/Forum.php');
include_once(__ROOT__ . '/classes/core/Log.php');

$log=$_SESSION['log'] = new Log ("info");
$_SESSION['message'] = "";

$FORUM = new Forum();

$forums = $FORUM->getTopLevelComments();
?>

<body>
<div class="container top"> 
    <?php include(__ROOT__.'/views/forum/forum_menu.php'); ?>

    <?php foreach($forums as $forum) { 
        ?>
        <div class="row">
            <div class="card-title"><h4><?php echo $forum['title'];?> </h4><h8>&emsp;&emsp;-- posted by <?php echo $forum['user_name'];?></h8></div>
        </div>
        <div class="row">
            <p class="card-text"><?php echo $forum['comment'];?></p>
            <?php if (strlen($forum['image']) > 5) {?>
            <img class="img-fluid" src="<?php echo $forum['image']; ?>" alt="Card image">
            <?php } ?>
       </div>
       <div class="row">
         <div class="offset-4 col-6">
        	<a href="/index.php?view=forum_topic&id=<?php echo $forum['id']; ?>" class="btn">Goto discussion on <?php echo $forum['title'];?></a>
        </div>
	 </div>
	 <hr/>
    <?php } ?>

</div>
<?php 
include(__ROOT__.'/views/_footer.php'); ?>
</body>