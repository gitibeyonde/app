<div class="container-fluid">

  <div class="row" style="background: #f4f6ff;padding-top: 22px;">
    
    <div class="col-lg-1 col-md-1">
    </div>
    
    <!-- <div class="col-lg-2 col-md-2">
        <form class="form-inline" action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo DELIVER_MICROAPP; ?>">
        <button type="submit" name="submit" value="microapp" style="background: transparent; border: 0;">
        <?php if (in_array($view, array(DELIVER_MICROAPP)))  { echo "<h3 style='text-decoration: underline;'>Deliver App</h3>";} else { echo "<h3>Deliver App</h3>"; }?></button>
        </form>
    </div> -->
    
    <div class="col-lg-2 col-md-2">
        <form action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo MESSAGE_TEMPLATE; ?>">
        <button type="submit" name="submit" value="run" style="background: transparent; border: 0;">
        <?php if (in_array($view, array(MESSAGE_TEMPLATE, MESSAGE_TEMPLATE_SMS, MESSAGE_TEMPLATE_EMAIL)))  { 
            echo "<h3 style='text-decoration: underline;'>Templates</h3>";} else { echo "<h3>Templates</h3>"; }?></button>
        </form>
    </div>
    
    <div class="col-lg-2 col-md-2">
        <form action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo SMS_TRIGGER; ?>">
        <button type="submit" name="submit" value="run" style="background: transparent; border: 0;">
        <?php if (in_array($view, array(SMS_TRIGGER)))  { echo "<h3 style='text-decoration: underline;'>Triggers</h3>";} else { echo "<h3>Triggers</h3>"; }?></button>
        </form>
    </div>
    
    <div class="col-lg-2 col-md-2">
        <form action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo SMS_SURVEY; ?>">
        <button type="submit" name="submit" value="add" style="background: transparent; border: 0;">
        <?php if (in_array($view, array(SMS_SURVEY)))  { echo "<h3 style='text-decoration: underline;'>Surveys</h3>";} else { echo "<h3>Surveys</h3>"; }?></button>
        </form>
    </div>
    
    <div class="col-lg-2 col-md-2">
        <form action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo SMS_AUDIENCE; ?>">
        <button type="submit" name="submit" value="db"  style="background: transparent; border: 0;">
        <?php if (in_array($view, array(SMS_AUDIENCE, AUDIENCE_DB_TABLE)))  { echo "<h3 style='text-decoration: underline;''>Audience</h3>";} else { echo "<h3>Audience</h3>"; }?></button>
        </form>
    </div>
</div>