

  <div class="row sel2" style="padding-top: 10px;">

    <div class="col" style="text-align: center;">
    	<?php if ($view == WIZ_WF_DESC) { ?><h3 style="text-decoration: underline">Header</h3> <?php } else { ?>
        <form action="/index.php"  method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo WIZ_WF_DESC; ?>">
        <button type="submit" name="submit" value="userdata" class="btn btn-sim5">
        <h3>Header</h3></button>
        </form>
        <?php } ?>
    </div>

    <div class="col" style="text-align: center;">
     	<?php if (in_array ( $view,  array (WIZ_WF_IMAGES, WIZ_WF_KB, WIZ_WF_KB_ADD, WIZ_WF_KB_TABLE, WIZ_WF_PAGES, WIZ_WF_GRAPH))) { ?>
        <h3 style="text-decoration: underline">Body</h3> <?php } else { ?>
        <form action="/index.php"  method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo WIZ_WF_IMAGES; ?>">
        <button type="submit" name="submit" value="microapp" class="btn btn-sim5">
       <h3>Body</h3></button>
        </form>
        <?php } ?>
    </div>

    <div class="col" style="text-align: center;">
    	<?php if ($view == WIZ_WF_PUBLISH) { ?><h3 style="text-decoration: underline">Publish</h3> <?php } else { ?>
        <form action="/index.php"  method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo WIZ_WF_PUBLISH; ?>">
        <button type="submit" name="submit" value="imageuplaod" class="btn btn-sim5">
        <h3>Publish</h3></button>
        </form>
        <?php } ?>
    </div>

</div>