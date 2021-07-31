<div class="container-fluid">

  <div class="row" style="background: #f4f6ff;padding-top: 22px;">

    <div class="col-lg-2 col-md-2">
        <form class="form-inline" action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo USER_ACCOUNT; ?>">
        <button type="submit" name="submit" value="microapp" style="background: transparent; border: 0;">
        <?php if (in_array($view, array(USER_ACCOUNT)))  { echo "<h3 style='text-decoration: underline;'>Account</h3>";} else { echo "<h3>Accoount</h3>"; }?></button>
        </form>
    </div>

    <div class="col-lg-2 col-md-2">
        <form action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo USER_USAGE; ?>">
        <button type="submit" name="submit" value="run" style="background: transparent; border: 0;">
        <?php if (in_array($view, array(USER_USAGE)))  {
            echo "<h3 style='text-decoration: underline;'>Usage</h3>";} else { echo "<h3>Usage</h3>"; }?></button>
        </form>
    </div>

    <div class="col-lg-2 col-md-2">
        <form action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo USER_BILLING; ?>">
        <button type="submit" name="submit" value="run" style="background: transparent; border: 0;">
        <?php if (in_array($view, array(USER_BILLING)))  { echo "<h3 style='text-decoration: underline;'>Billing</h3>";} else { echo "<h3>Billing</h3>"; }?></button>
        </form>
    </div>

    <div class="col-lg-2 col-md-2">
        <form action="/index.php"  method="get">
        <input type=hidden name=view value="<?php echo USER_HOST_KEY; ?>">
        <button type="submit" name="submit" value="add" style="background: transparent; border: 0;">
        <?php if (in_array($view, array(USER_HOST_KEY)))  { echo "<h3 style='text-decoration: underline;'>Key</h3>";} else { echo "<h3>Key</h3>"; }?></button>
        </form>
    </div>

</div>