<?php
include_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once (__ROOT__ . '/classes/sms/SmsImages.php');
include_once (__ROOT__ . '/classes/sms/SmsMinify.php');
include_once (__ROOT__ . '/classes/core/Icons.php');
include_once (__ROOT__ . '/classes/core/Log.php');

include (__ROOT__ . '/views/_header.php');
$log = $_SESSION ['log'] = new Log ( 'debug' );

$user_id = $_SESSION ['user_id'];

$WFDB = new WfMasterDb ();
$wfs = $WFDB->getWorkflows ( $user_id );

$min = new SmsMinify ();
$SI = new SmsImages ();
$logo = "/img/ico192.png";
$Icon = new Icons ();

$min = new SmsMinify ();
?>
<body>
<div class="container" style="padding-top: 100px;">
    <div class="row">
        <div class="col-12">
             <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_types.html");'>
             <i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
             <h3>Catalogs</h3>
        </div>
    </div>
        <?php
          foreach ( $wfs as $wf ) {
            $lg = $SI->logo ( $wf ['bot_id'] );
            $url = $min->createMicroAppUrl ( 95, $wf ['bot_id'] );
            $url_otp = $min->createOwnerUrl ( $user_id, $wf ['bot_id'], $_SESSION ['user_phone'], $_SESSION ['user_email'] );
            ?>
        	<div class="row">
        			<div class="col-12">
        				<hr/>
                    	<h4><?php echo $wf['name']; ?></h4>
                    </div>
                    <div class="col-6">
                    	<a href='https://<?php echo $url; ?>' target='_blank' class="btn-sim3">
                    	<img class="img-fluid" src='<?php echo $lg; ?>' height="100px">
                        </a>
                    </div>
                    <div class="col-6">
                            <form action="/index.php" method="get">
                                <input type=hidden name=bot_id value="<?php echo $wf ['bot_id']; ?>">
                                <input type=hidden name=view value="<?php echo MOBILE_USER_DATA; ?>">
                                <button type="submit" name="submit" value="userdata" class="btn btn-block btn-sim3">Data</button>
                            </form>
                           <a href="https://<?php echo $url_otp; ?>" class="btn btn-block btn-sim3" target="_blank">Scanner</a>
                   </div>
             	<hr/>
        	</div>
       <?php } ?>
   <div class="row">
        <div class="col-12">
            <hr/>
            <h8>To create catalogs login from a desktop or laptop.</h8>
        </div>
    </div>

</div>
<br/>
<br/>
<?php
include (__ROOT__ . '/views/_footer.php');
?>
</body>