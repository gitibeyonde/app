<?php
include(__ROOT__.'/views/_header.php');
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/sms/SmsLog.php');

$log = $_SESSION['log'] = new Log('debug');
$user_id = $_SESSION['user_id'];
$bot_id=$_GET['bot_id'];

$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);

$Sl = new SmsLog();
$messages = $Sl->getSmsLogForBot($bot_id);
?>
<body>
<div class="container-fluid" style="padding-top: 100px;"> 
        <?php include(__ROOT__.'/views/sms/dash_menu_top.php'); ?>

    <div class="row">
        <div class="col-lg-2 col-md-2">
            <h4><?php echo $wf['name']; ?></h4>
            <h5><?php echo $wf['category']; ?></h5>
        </div><!-- End first Column -->
        
        
        <div class="col-lg-7 col-md-7">
            <h3>Sms Log</h3>
                       
       	   <table  class="table table-striped" width="100%">
           <?php
            if ($messages == null || count($messages) == 0) {
                echo "<tr><td colspan=5><font style='color: #3862c6;'>No message templates found, create one !</font></td></tr>";
            } else {
           ?> 
           <tr>
           <th>Type</th>
           <th>Number </th>
           <th>Sms</th>
          </tr>
           <?php    foreach ($messages as $map) { ?>
            <tr width="100%">
            <td><?php echo $map['type']; ?></td>
            <td><?php echo $map['there_number']; ?></td>
            <td><?php echo $map['sms']; ?></td>
            </tr>
        <?php }  } ?>
        </table>
       </div>
       
   </div>
          
</div>     

</body>
<?php 
include(__ROOT__.'/views/_footer.php');
?>