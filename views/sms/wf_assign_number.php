<?php
require_once (__ROOT__ . '/classes/device/GsmDevice.php');
require_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/core/Log.php');

$_SESSION['log'] = new Log ("info");

$bot_id = $_GET['bot_id'];
$user_id = $_SESSION['user_id'];
$gsmdev = new GsmDevice();
$my_numbers = $gsmdev->getGsmDevice($user_id);
$wfmdb = new WfMasterDb();

include (__ROOT__.'/views/_header.php');

$submit=isset($_GET['submit']) ? $_GET['submit'] : null;
if ($submit == "detach") {
    $number=$_GET['number'];
    $from_bot_id = $_GET['from_bot_id'];
    $wfmdb->detachNumber($user_id, $from_bot_id, $number);
}
else if ($submit == "attach") {
    $number=$_GET['number'];
    $wfmdb->attachNumber($user_id, $bot_id, $number);
}
$botwf = $wfmdb->getWorkflow($bot_id);
?>
<body>
<main>
<div class="container">
<div class="row">
    <form action="/index.php"  method="get" style="float: left;">
    <input type=hidden name=view value="main_view">
    <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
    <button class="btn btn-sim1" type="submit" name="submit" value="db"><i class="fas fa-arrow-left fa-2x"  style="color: orange"></i></button>
    </form>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <br/>
</div>
    <div class="row">
    <br/>
        <h4>Attached Devices</h4>
         <br/>
         <br/>
        
        <hr/>
         <br/>
         <br/>
         <br/>
    </div>
     <br/>
     <br/>
     <br/>
     <br/>
     <br/>
    <div class="row">
                <?php foreach ($my_numbers as $num) { 
                    $wf = $wfmdb->getWorkflowForNumber($num['my_number']);
                    ?>
                  <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                    <div class="card mb-4 box-shadow">
                        <div class="card-image">
                            <p> Device Pings </p>
                            <div class="embed-responsive embed-responsive-4by3">
                                    <iframe class="embed-responsive-item" scrolling="no" frameborder="0" width="432" height="324" id="<?php echo $num['uuid']; ?>0" style="display: block"
                                        src="views/graph/gsm_health.php?uuid=<?php echo $num['uuid']; ?>"> </iframe>
                            </div>
                         </div>
                           <div class="card-body">
                            <div class="flex-container">
                              <small style="cursor: pointer;">Device Id <b><?php echo $num['uuid'];  ?></b></small>
                              <small style="cursor: pointer;">Installed Number <b><?php echo $num['my_number']; ?></b> </small>
                              <br/>
                              <form action="/index.php" method="get">
                                <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                                <input type=hidden name=view value="assign_number">
                                <input type=hidden name=number value="<?php echo $num['my_number']; ?>">
                              <?php if ($wf != null){ ?>
                                <button type="submit" name="submit" value="detach" class="btn btn-link">
                                        <input type=hidden name=from_bot_id value="<?php echo $wf['bot_id']; ?>">
                                        Detach from <?php echo $wf['name']; ?></button>
                              <?php } else { ?>
                                <button type="submit" name="submit" value="attach" class="btn btn-link">
                                        Attach to <?php echo $botwf['name']; ?></button>
                              <?php } ?>
                              </form>
                              </div>
                             </div>
                       </div>
                     </div>
                <?php 
                $wf = null;
                }  ?> 
    </div>

 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
   
</div>    
</main>

<?php 
include(__ROOT__.'/views/_footer.php');
?>