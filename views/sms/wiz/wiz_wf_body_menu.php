

<br/>
  <div class="row sel0" style="padding-top: 10px;">

    <div class="col" style="text-align: center;">
        <?php if ($view == WIZ_WF_IMAGES) { ?><h3 style="text-decoration: underline">Image</h3> <?php } else { ?>
        <form action="/index.php"  method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo WIZ_WF_IMAGES; ?>">
        <button type="submit" name="submit" value="userdata" class="btn btn-sim5">
        <h3>Image</h3></button>
        </form>
        <?php } ?>
    </div>

    <div class="col" style="text-align: center;">
        <?php if (in_array ( $view,  array (WIZ_FORM_CREATE, WIZ_FORM_UI))) { ?>
        <h3 style="text-decoration: underline">Forms</h3> <?php } else { ?>
        <form action="/index.php"  method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo WIZ_FORM_CREATE; ?>">
        <button type="submit" name="submit" value="microapp" class="btn btn-sim5">
       <h3>Forms</h3></button>
        </form>
        <?php } ?>
    </div>

    <div class="col" style="text-align: center;">
     	<?php if (in_array ( $view,  array (WIZ_WF_KB, WIZ_WF_KB_ADD, WIZ_WF_KB_TABLE))) { ?>
        <h3 style="text-decoration: underline">KB-Tables</h3> <?php } else { ?>
        <form action="/index.php"  method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo WIZ_WF_KB; ?>">
        <button type="submit" name="submit" value="microapp" class="btn btn-sim5">
       <h3>KB-Tables</h3></button>
        </form>
        <?php } ?>
    </div>

    <div class="col" style="text-align: center;">
        <?php if ($view == WIZ_WF_PAGES) { ?><h3 style="text-decoration: underline">Pages</h3> <?php } else { ?>
        <form action="/index.php"  method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo WIZ_WF_PAGES; ?>">
        <button type="submit" name="submit" value="imageuplaod" class="btn btn-sim5">
        <h3>Pages</h3></button>
        </form>
        <?php } ?>
    </div>

    <div class="col" style="text-align: center;">
        <?php if ($view == WIZ_WF_GRAPH) { ?><h3 style="text-decoration: underline">Graph</h3> <?php } else { ?>
        <form action="/index.php"  method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo WIZ_WF_GRAPH; ?>">
        <button type="submit" name="submit" value="imageuplaod" class="btn btn-sim5">
        <h3>Graph</h3></button>
        </form>
        <?php } ?>
    </div>

</div>
<br/>