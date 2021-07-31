<?php
include_once(__ROOT__ . '/classes/core/Log.php');
include_once(__ROOT__ . '/classes/wf/data/WfMasterDb.php');
include_once(__ROOT__ . '/classes/sms/SmsMinify.php');
include_once(__ROOT__ . '/classes/sms/SmsUtils.php');
include_once(__ROOT__ . '/classes/device/GsmDevice.php');
include_once(__ROOT__ . '/classes/sms/SmsImages.php');

$log = $_SESSION['log'] = new Log('debug');

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$bot_id=$_GET['bot_id'];

if ($bot_id == null){
    echo "<body><main>Fatal Error !";
    include(__ROOT__.'/views/_footer.php');
    die;
}
$WFDB = new WfMasterDb();
$wf = $WFDB->getWorkflow($bot_id);

$min = new SmsMinify();

$submit=isset($_GET['submit']) ? $_GET['submit'] : null;
if ($submit == "change_validation"){
    $add_format = intval($_GET['format']);
    $format = intval($WFDB->getFormat($bot_id));
    $format = $format & ~16;
    $format = $format & ~32;
    $new_format = $format | $add_format;
    //change validation
    //error_log("Add=".$add_format." Format=".$format." New format=". $new_format);
    $WFDB->setFormat($user_id, $bot_id, $new_format);
}
else if ($submit == "set_css"){
    $css = $_GET['css'];
    $WFDB->updateCss($user_id, $bot_id, $css);
}
else if ($submit == "map_url"){
    $url = $_GET['to_map_url'];
    $min->createMapForUserName($url, $user_id, $user_name);
}

$wf = $WFDB->getWorkflow($bot_id);

$url = "https://".$min->createMicroAppUrl($user_id, $bot_id);
$url_otp = "https://".$min->createMicroAppUrlOtp($user_id, $bot_id);

$html_otp = urlencode("https://".$url_otp);

$chat_url = "https://".$min->createChatUrl($user_id, $bot_id);

$SI = new SmsImages();
$lg= "/img/ico192.png";
$lg = $SI->logo($bot_id);


$otp_type = (intval($wf['status']) & 16 ) == 16 ? 'email' :  'number';
if ($otp_type == "email"){
    $email_checked="checked";
    $number_checked="";
}
else {
    $email_checked="";
    $number_checked="checked";
}
$owner_url_otp = "https://".$min->createOwnerUrl ( $user_id, $bot_id, $_SESSION ['user_phone'], $_SESSION ['user_email'] );
include(__ROOT__.'/views/_header.php');
?>

<body>
<div class="container top">
    <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_menu.php'); ?>
    <br/>
    <div class="row">
       <div class="col-lg-12 col-md-12 table-responsive"><a href="javascript:void(0)" onclick='pop_up("
           <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_publish.html");'>
                    <i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
            <h4>Publish the catalog</h4>
            <p>There are various options available to publish the catalog, like with or without OTP.
            </form>
            </p>
       </div>
    </div>

        <div class="row">
            <div class="col-lg-6 col-md-6">
                <table class="table-responsive" style="background-color: floralwhite;">
                    <tr>
                        <td colspan=2">
                            <form class="form-inline" action="/index.php"  method="get" style="float: left;">
                             <label>&emsp;&emsp;Choose Skin&emsp;&emsp;</label>
                            <select  class="form-control" name="css">
                                   <option value="light" <?php echo ($wf['css']=="light" ? "selected" : ""); ?>>light</option>
                                   <option value="black" <?php echo ($wf['css']=="black" ? "selected" : ""); ?>>black</option>
                                   <option value="white" <?php echo ($wf['css']=="white" ? "selected" : ""); ?>>white</option>
                                   <option value="blue" <?php echo ($wf['css']=="blue" ? "selected" : ""); ?>>blue</option>
                               </select>
                               <input type=hidden name=view value="<?php echo WIZ_WF_PUBLISH; ?>">
                            <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
                            &emsp;&emsp;<button  type="submit" name="submit" value="set_css"  class="form-control btn btn-sim1">Set</button>
                            </form>
                        </td>
                    </tr>
                 </table>

            </div>
            <div class="col-lg-6 col-md-6">
                <table class="table-responsive" style="background-color: floralwhite;">
                    <tr>
                        <td colspan=2>
                           <form class="form-inline" action="/index.php"  method="get">
                             <label>Validation Mode&emsp;&emsp;</label>
                              <div class="form-inline">
                                <input type="radio"  name="format" value="16" <?php echo $email_checked; ?>>
                                <label for="male">&emsp;Email&emsp;</label>
                                <input type="radio"  name="format" value="32" <?php echo $number_checked; ?>>
                                <label for="male">&emsp;Number&emsp;</label>
                              </div>
                               <input type=hidden name="bot_id" value="<?php echo $wf['bot_id']; ?>">
                               <input type=hidden name=view value="<?php echo WIZ_WF_PUBLISH; ?>">
                               <button type="submit" name="submit" value="change_validation" class="btn btn-sim1">Set</button>
                           </form>
                        </td>
                    </tr>
                 </table>
            </div>
      </div>

        <div class="row">
            <div class="col-lg-6 col-md-6">
                <table width="100%">
                    <tr>
                        <td colspan=2  style="height: 15vh;text-align: center;">
                        Catalog Link: <?php
                        list($url, $map_exists) = $min->getMappedUrlFor($url, $user_id, $user_name);
                        ?>
                        <a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a>
                        <?php if (!$map_exists){ ?>
                            <form action="/index.php"  method="get">
                            <input type=hidden name="to_map_url" value="<?php echo $url; ?>">
                               <input type=hidden name="bot_id" value="<?php echo $wf['bot_id']; ?>">
                            <input type=hidden name=view value="<?php echo WIZ_WF_PUBLISH; ?>">
                            <button type="submit" name="submit" value="map_url" class="btn btn-sim4">Map to Username</button>
                            </form>
                         <?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td><img src="<?php echo $lg; ?>" width="80vw"></td>
                        <td><h3><?php echo $wf['name']; ?></h3></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;"><img src="https://www.deltacatalog.com/classes/core/QRCode.php?f=png&s=qr-q&d=<?php echo $url; ?>&sf=8&ms=r&md=0.8" width="100vw"></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;">
                                <a href="https://www.deltacatalog.com/sample_app.php?link=<?php echo $chat_url; ?>" target="_blank">Open As Chat</a></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;"><?php echo $chat_url; ?></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;">
                                <a href="<?php echo $url; ?>" target="_blank">Open As App</a></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;"><?php echo $url; ?></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;padding-top: 20px;">
                        <a class="btn btn-sim3" href="#" onclick="pop_up_long('/views/sms/wiz/wiz_wf_print.php?type=basic&bot_id=<?php echo $wf['bot_id'];?>');">print</a></td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6 col-md-6">
                <table width="100%">
                    <tr>
                        <td colspan=2 style="height: 15vh;text-align: center;">This QR-code and link are for OTP(<?php echo $otp_type; ?>) enabled Catalog.
                        Please note that OTP-enabled catalogs will give you access to taking payments and communicating with customers
                        from dashboard.</td>
                    </tr>
                    <tr>
                        <td><img src="<?php echo $lg; ?>" width="80vw"></td>
                        <td><h3><?php echo $wf['name']; ?></h3></td>
                    </tr>
                    <tr>
                        <td colspan=2></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;"><img src="https://www.deltacatalog.com/classes/core/QRCode.php?f=png&s=qr-q&d=<?php echo $html_otp; ?>&sf=8&ms=r&md=0.8" width="100vw"></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;">
                               Validated App:
                               <?php
                               list($url_otp, $map_exists) = $min->getMappedUrlFor($url_otp, $user_id, $user_name);
                                ?>
                               <a href="<?php echo $url_otp; ?>" target="_blank"><?php echo $url_otp; ?></a>
                                <?php if (!$map_exists){ ?>
                                    <form action="/index.php"  method="get">
                                    <input type=hidden name="to_map_url" value="<?php echo $url_otp; ?>">
                                    <input type=hidden name="bot_id" value="<?php echo $wf['bot_id']; ?>">
                                    <input type=hidden name="view" value="<?php echo WIZ_WF_PUBLISH; ?>">
                                    <button type="submit" name="submit" value="map_url" class="btn btn-sim4">Map to Username</button>
                                    </form>
                                 <?php }?>
                            </td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;"></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;">
                                Scanner App: <a href="<?php echo $owner_url_otp; ?>" target="_blank"><?php echo $owner_url_otp; ?></a>
                            </td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align: center;padding-top: 20px;">
                        <a class="btn btn-sim3" href="#" onclick="pop_up_long('/views/sms/wiz/wiz_wf_print.php?type=otp&bot_id=<?php echo $wf['bot_id'];?>');">print</a></td>
                    </tr>
                </table>
            </div>
        </div> <!--  end row -->
</div>  <!-- end container -->

<?php
include(__ROOT__.'/views/_footer.php');
?>
</body>
</html>