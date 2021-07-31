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


$mywf = $WFDB->getAllWorkflows();

include(__ROOT__.'/views/_header.php');
?>
<body>

<div class="container" style="padding-top: 120px;">


   <?php if (sizeof($mywf) == 0) { ?>
   
   	<h2>Welcome to catalog maker</h2>
   	<hr/>
   	<p>
   Lets go about building your first catalog-app. If you have pictures of your product or items that will be nice. So lets start ..
   	</p>
  <div class="row"  style="padding-top: 20px;">
    <div class="col-md-4">
       <form class="form-inline"  action="/index.php"  method="get">
         <input type=hidden name=view value="<?php echo WIZ_WF_CATEGORY; ?>">
    	 <button class="btn btn-block btn-sim1">Create your first catalog app</button>
    	</form>
    </div>
    </div>
   <?php } ?>
   
  
   <?php if (sizeof($mywf) > 0) { ?>
<div class="row">
    <div class="col-md-4">
       <form class="form-inline"  action="/index.php"  method="get">
         <input type=hidden name=view value="<?php echo WIZ_WF_CATEGORY; ?>">
    	 <button class="btn btn-block btn-sim1">Create New Catalog</button>
    	</form>
    </div>
</div>
<ul>	
    <li style="padding: 10px;">
      <!-- my apps -->
        <div class="panel-group" id="accordion1" style="padding-top: 12px;">
            <div class="panel panel-default">
                <div class="panel-heading">
                     <a class="user-guide-panel" data-toggle="collapse"  data-target="#collapseZero">
                         <h3>My Catalogs <i class="ti-hand-point-right"></i></h3>
                     </a>
                </div>
            <div id="collapseZero" class="panel-collapse">
                <div class="panel-body"> 
                  <table class="table">
                      <?php foreach ($mywf as $wf) {
                          if (in_array ($wf['user_id'], array(95, 190)))continue;
                          if (!in_array ($wf['category'], array ("basic", "catalogue", "menu", "visitor", "invitation", "survey", "search")))continue;
                           $lg = $SI->logo($wf['bot_id']); 
                           $url = "https://".$min->createMicroAppUrl($user_id, $wf['bot_id']);
                           
                           ?>
                           <tr>
                           <td style='padding: 5px;'>
                               <h4><?php echo $wf['user_id']; ?></h4>
                           </td>
                           <td style='padding: 5px;'>
                               <img src='<?php echo $lg; ?>'   width="100px">
                               <h4><?php echo $wf['name']; ?></h4>
                           </td>
                           <td style='padding: 5px;text-align: center;'><h6><?php echo $wf['description']; ?></h6>
                           <a href="<?php echo $url;?>" target="_blank"><?php echo $url; ?></a>
                           </td>
                           
                           <td style='padding: 5px;'>
                              <table class="table">
                              <tr><td  style="padding-bottom: 5px;">
                               <form class="form-inline"  action="/index.php"  method="get">
                               <input type=hidden name=view value="<?php echo WIZ_WF_DESC; ?>">
                               <input type=hidden name=bot_id value="<?php echo $wf['bot_id']; ?>">
                               <button type="submit" name="submit" value="submit" class="btn btn-sim1" style="width: 100%;">Edit</button>
                               </form>
                              </td></tr>
                              <tr><td  style="padding-bottom: 5px;">
                               <form class="form-inline"  action="/index.php"  method="get">
                               <input type=hidden name=view value="<?php echo USER_DATA; ?>">
                               <input type=hidden name=bot_id value="<?php echo $wf['bot_id']; ?>">
                               <button type="submit" name="submit" value="submit" class="btn btn-sim1" style="width: 100%;">Dashboard</button>
                               </form>
                              </td></tr>
                              <tr><td  style="padding-bottom: 5px;">
                                 <form class="form-inline" action="/index.php"  method="get"  onsubmit="return confirm('Do you really want delete this Catalog App ?');">
                                    <input type=hidden name=bot_id value="<?php echo $wf['bot_id']; ?>">
                                    <input type=hidden name=view value="<?php echo MAIN_VIEW; ?>">
                                    <button type="submit" name="submit" value="choose_delete" class="btn btn-sim2" style="width: 100%;">Delete</button>
                                  </form>
                              </td></tr>
                              </table>
                           </td>
                           </tr>
                     <?php } ?>
                    </table>
                </div>
            </div>
          </div>
       </div>
    </li>
</ul>
<?php }  ?>

</div>

<?php 
include(__ROOT__.'/views/_footer.php');
?>
</body>