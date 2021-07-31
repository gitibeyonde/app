<?php
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/sms/SmsMinify.php');
require_once (__ROOT__ . '/classes/sms/SmsImages.php');
include_once(__ROOT__ . '/classes/wf/data/WfData.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
include_once(__ROOT__ . '/classes/sms/SmsImages.php');
include_once(__ROOT__ . '/classes/sms/SmsMinify.php');

$log = $_SESSION['log'] = new Log('info');
$user_id = $_SESSION['user_id'];

$submit = isset($_GET["submit"]) ? $_GET["submit"] : null;

$WFDB = new WfMasterDb();

$min = new SmsMinify();

$SI = new SmsImages();

if ($submit=="choose_delete"){
    $bot_id = $_GET['bot_id'];
    $WFDB->deleteWorkflow($bot_id);
    WfDb::deleteBotStore($user_id, $bot_id);
    $Im = new SmsImages();
    $Im->deleteImageFolder($bot_id);
}


$mywf = $WFDB->getWorkflows($user_id);

include(__ROOT__.'/views/_header.php');
?>
<body>

<div class="container top">


   <?php if (sizeof($mywf) == 0) { ?>

   	<h2>Welcome to catalog maker</h2>
   	<hr/>
  <div class="row"  style="padding-top: 20px;">
    <div class="col-md-4">
       <form class="form-inline"  action="/index.php"  method="get">
         <input type=hidden name=view value="<?php echo WIZ_WF_CATEGORY; ?>">
    	 <button class="btn btn-block btn-sim1">Create your first catalog app</button>
    	</form>
    </div>
   </div>
   <br/>
  <div class="row">
        <iframe src="/catalog-maker/docs/catalog_types.html"
                    class="embed-responsive-item" scrolling="no" frameborder="0" width="1060" height="700"></iframe>
   </div>
   <?php } else { ?>
<div class="row">
    <div class="col-md-3 col-8">
       <form class="form-inline"  action="/index.php"  method="get">
         <input type=hidden name=view value="<?php echo WIZ_WF_CATEGORY; ?>">
    	 <button class="btn btn-block btn-sim1">Create New Catalog</button>
    	</form>
    </div>
    <div class="col-md-5 col-2">
    </div>
    <div class="col-md-4 col-2">
         <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_types.html");'>
         <i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
    </div>
</div>
      <!-- my apps -->
                 <hr/>

              <?php foreach ($mywf as $wf) {
                  if (!in_array ($wf['category'], array ("basic", "catalogue", "menu", "visitor", "invitation", "survey", "search")))continue;
                   $lg = $SI->logo($wf['bot_id'])."?".rand();
                   $url = "https://".$min->createMicroAppUrl($user_id, $wf['bot_id']);
                   $url_otp = $min->createOwnerUrl ( $user_id, $wf ['bot_id'], $_SESSION ['user_phone'], $_SESSION ['user_email'] );
                   ?>
                    <div class="row" style="margin-bottom: 30px;">
                           <div class="col">
                               <a href="<?php echo $url;?>" target="_blank"><img src='<?php echo $lg; ?>'   width="90px"></a>
                               <a href="<?php echo $url;?>" target="_blank"><h4><?php echo $wf['name']; ?></h4></a>
                           </div>
                           <div class="col">
                                <img class="img-fluid my-auto" style="min-width: 100px;"  src="https://www.deltacatalog.com/classes/core/QRCode.php?f=png&s=qr-q&d=<?php echo $url; ?>&sf=8&ms=r&md=0.8">
                           </div>
                           <div class="col" style='padding: 5px;text-align: center;'><h6><?php echo $wf['description']; ?></h6>
                               <a href="<?php echo $url;?>" target="_blank"><?php echo $url; ?></a>
                           </div>
                           <div class="col">
                              <div class="row">
                                   <div class="col-6"  style="padding-bottom: 5px;">
                                       <form class="form-inline"  action="/index.php"  method="get">
                                       <input type=hidden name=view value="<?php echo WIZ_WF_DESC; ?>">
                                       <input type=hidden name=bot_id value="<?php echo $wf['bot_id']; ?>">
                                       <button type="submit" name="submit" value="submit" class="btn btn-block btn-sim4">Edit</button>
                                       </form>
                                   </div>
                                   <div class="col-6"  style="padding-bottom: 5px;">
                                       <form class="form-inline"  action="/index.php"  method="get">
                                       <input type=hidden name=view value="<?php echo USER_DATA; ?>">
                                       <input type=hidden name=bot_id value="<?php echo $wf['bot_id']; ?>">
                                       <button type="submit" name="submit" value="submit" class="btn btn-block btn-sim4">Data</button>
                                       </form>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-8"  style="padding-bottom: 5px;">
                                        <a href="https://<?php echo $url_otp; ?>" class="btn btn-block btn-sim4" target="_blank">Scanner</a>
                                   </div>
                                   <div class="col-4"  style="padding-bottom: 5px;">
                                        <form class="form-inline" action="/index.php"  method="get"  onsubmit="return confirm('Do you really want delete this Catalog App ?');">
                                        <input type=hidden name=bot_id value="<?php echo $wf['bot_id']; ?>">
                                        <input type=hidden name=view value="<?php echo MAIN_VIEW; ?>">
                                        <button type="submit" name="submit" value="choose_delete" class="btn btn-block btn-sim2"><i class="ti-trash" style="color: red;"></i></button>
                                        </form>
                                   </div>
                              </div>
                          </div>
                    </div>
             <?php } ?>
<?php }  ?>

</div>

<?php
include(__ROOT__.'/views/_footer.php');
?>
</body>