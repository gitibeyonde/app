
<div class="row" style="background: #f4f6ff; margin-bottom: 20px;">
    <div class="col-lg-3 offset-lg-2 col-md-4 offset-md-2 col-sm-6 col-6">
    	<?php if ($view == FORUM_LISTING) { ?><h3 style="text-decoration: underline">Topic List</h3> <?php } else { ?> 
        <form action="/index.php" method="get">
            <input type=hidden name=view value="<?php echo FORUM_LISTING; ?>">
            <button type="submit" name="submit" value="create_form" class="btn btn-sim5">
                <h3>Topic List</h3>
            </button>
        </form>
        <?php } ?>
    </div>
    <div class="col-lg-3col-md-4 col-sm-6 col-6">
        <?php 
        if ($view == FORUM_CREATE_TOPIC) { ?><h3 style="text-decoration: underline">Create Topic</h3> <?php } 
        else { ?> 
        <form action="/index.php" method="get">
            <input type=hidden name=view value="<?php echo FORUM_CREATE_TOPIC; ?>">
            <button type="submit" name="submit" value="arrange_forms" class="btn btn-sim5">
                <h3>Create Topic</h3>
            </button>
        </form>
        <?php } ?>
    </div>
</div>