<style>
.wf-seed-viewer {
  
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
  border-radius: 11px;
  display: block;
  position: fixed;
  border: none;
  width: 600px;
  height: 1000px;
}
</style>
<?php
include(__ROOT__.'/views/_header.php');
include_once(__ROOT__ . '/classes/sms/SmsMinify.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');

$log = $_SESSION['log'] = new Log('debug');
$user_id=$_SESSION['user_id'];

$bot_id=isset($_GET['bot_id']) ? $_GET['bot_id'] : null;
$submit=isset($_GET['submit']) ? $_GET['submit'] : null;

$min = new SmsMinify();
$url = "http://".$min->createMicroAppUrl($user_id, $bot_id);


$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);
?>


<body>
<div class="container-fluid" style="padding-top: 100px;">
 <div class="row">
        <?php include(__ROOT__.'/views/sms/agent_wf_menu_top.php'); ?>
    </div>

    <div class="row">
        <div class="col-lg-2 col-md-2">
           
        </div><!-- End first Column -->
        
        <div class="col-lg-7 col-md-7">
            <br/>
            <br/>
            <h4>App Player</h4>
            <br/>
            <hr/>

            <div class="row">
              <div class="col-lg-6  col-md-6 d-lg-block d-md-block d-none d-sm-none">
              </div>
              
              <div class="col-lg-6  col-md-6 d-lg-block d-md-block d-none d-sm-none" style="padding: 20px;height: 1200px;">
                 <iframe class="wf-seed-viewer" id="myForm"
                 src="<?php echo $url; ?>" 
                 frameborder="0" scrolling="no">
                 </iframe>
              </div>
            </div>
        </div>
    </div>
  
</div>
</body>

<?php include(__ROOT__.'/views/_footer.php');?>