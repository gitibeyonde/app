  <div class="row" style="background: #f4f6ff;">
    
    <div class="col-lg-2 col-md-2">
    
    </div>

    <div class="col-lg-2 col-md-2">
        <?php if ($view == USER_DATA || $view == USER_DATA_TABLE || $view == USER_REPORT) { ?>
        <h3  style="text-decoration: underline">Database</h3> <?php } else { ?>
        <form action="/index.php"  method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo USER_DATA; ?>">
        <button type="submit" name="submit" value="userdata" class="btn btn-sim5"> 
        <h3>Database</h3></button>
        </form>
        <?php } ?>
    </div>
    
    <div class="col-lg-2 col-md-2">
    <?php if ($view == WORKFLOW_MESSAGES) { ?><h3  style="text-decoration: underline">Logs</h3> <?php } else { ?> 
        <form action="/index.php" method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo WORKFLOW_MESSAGES; ?>">
        <button type="submit" name="submit" value="imageuplaod" class="btn btn-sim5">
        <h3>Logs</h3></button>
        </form>
        <?php } ?>
    </div>
    
    <div class="col-lg-2 col-md-2">
    <?php if ($view == PAYMENT_SETUP) { ?><h3  style="text-decoration: underline">Payment</h3> <?php } else { ?> 
        <form action="/index.php"  method="get">
        <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
        <input type=hidden name=view value="<?php echo PAYMENT_SETUP; ?>">
        <button type="submit" name="submit" value="payment" class="btn btn-sim5">
        <h3>Payment</h3></button>
        </form>
        <?php } ?>
    </div>
  
  </div>
