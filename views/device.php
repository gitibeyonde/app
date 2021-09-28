<?php include('_header.php'); ?>
<?php

require_once (__ROOT__ . '/classes/UserFactory.php');
require_once (__ROOT__ . '/classes/User.php');
require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/classes/Sip.php');
require_once (__ROOT__ . '/classes/Aws.php');
require_once (__ROOT__ . '/classes/Utils.php');
require_once (__ROOT__ . '/classes/DeviceCert.php');
require_once (__ROOT__.'/classes/RegistryPort.php');
require_once(__ROOT__.'/classes/DeviceContext.php');

// error_reporting(E_ERROR | E_PARSE);

$user_id = $_SESSION ['user_id'];
$user_name = $_SESSION ['user_name'];
$uuid = $_GET ['uuid'];
$device_name = $_GET ['device_name'];
$user_email = $_SESSION ['user_email'];
$thisbox = 'default';
if (isset ( $_GET ['box'] )) {
    $thisbox = $_GET ['box'];
}
$utils = new Utils ();
$check_uuid = $utils->validateDevice ( $user_name, $uuid );
if ($check_uuid != $uuid) {
    echo "Bad access";
    include ('_footer.php');
    return;
}

$user = UserFactory::getUser ( $user_name, $user_email );
$device = $user->getDevice ( $uuid );
$boxes = $user->getBoxes ();
$pr = new RegistryPort();

set_time_limit ( 5 );
$client = new Aws ();
$timezone = $device->timezone ;
$today = Utils::dateNow ( $timezone);
list ( $furl, $datetime ) = $client->latestMotionDataUrl ( $device->uuid, $today );
list($ip, $port) = $pr->getIpAndPort($device->uuid);
?>

<div class="container"  style="padding-top: 100px;">

     <div class="row" style="background-color: lightblue;">
        <div class="col-sm-12 col-md-6">
            <form class="form-horizontal" name=changeDeviceName method=GET action="sql_action.php">
                <label> Capabilities :


                    &nbsp;&nbsp;
                    <?php if (strpos($device->capabilities, "CAMERA") !== false) { ?>
                        <span class="glyphicon glyphicon-camera"> </span>
                    <?php } ?>
                    <?php if (strpos($device->capabilities, "SIP") !== false) { ?>
                        <span class="glyphicon glyphicon-phone-alt"> </span>
                    <?php } ?>
                    <?php if (strpos($device->capabilities, "TEMPERATURE") !== false) { ?>
                        <span class="glyphicon glyphicon-scale"> </span>
                    <?php } ?>
                    <?php if (strpos($device->capabilities, "MOTION") !== false) { ?>
                        <span class="glyphicon glyphicon-picture"> </span>
                    <?php } ?>
                    <?php if (strpos($device->capabilities, "BELL") !== false) { ?>
                        <span class="glyphicon glyphicon-bell"> </span>
                    <?php } ?>

                  &nbsp;&nbsp;
                    Name:
                </label>

                <input type="text" name="device_name" value="<?php echo $device_name; ?>" style="width: 6em; height: 2em;"/>
                <input type=hidden name=view value="<?php echo DEVICE_VIEW ?>" /> <input type=hidden name=action value="ChangeDeviceName" /> <input type=hidden name=uuid
                    value="<?php echo $device->uuid ?>" />  <input type="hidden" name="box" value="<?php echo $device->box_name;?>" /><input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                    <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                    <input class="btn btn-sm btn-link" type="submit" type=submit name="submit" value="Change" />
            </form>
        </div>
        <div class="col-sm-12 col-md-6" style="float: right;">
         <table>
            <tr>
                <td><b>Device is in <code><?php echo $device->box_name; ?></code> box.</b>
                </td>
                <td><b>&nbsp;&nbsp; Move to</td>
                <?php

                foreach ( $boxes as $box ) {
                    if (strcmp ( $device->box_name, $box ) == 0) {
                        continue;
                    }
                    ?>
                    </b>
                <td>
                    <form class="form-horizontal" name=moveToBox method=GET action="sql_action.php">
                        <input type=hidden name=view value="<?php echo DEVICE_VIEW ?>" /> <input type=hidden name=action value="MoveToBox" /> <input type=hidden name=uuid
                            value="<?php echo $device->uuid ?>" /> <input type=hidden name=device_name value="<?php echo $device_name; ?>" />
                            <input type="hidden" name="box" value="<?php echo $box;?>" />
                            <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                                <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                        <input class="btn btn-sm btn-link" type="submit" type=submit name="submit" value="<?php echo $box; ?>," />
                    </form>
                </td>
                <?php } ?>
            </tr>

        </table>
      </div>
    </div>

     <div class="row">
        <div class="col-sm-12 col-md-6">
            <b>Version : <?php echo $device->version ?></b><br/>
            <b>Local Url : <a href="http://<?php echo $device->deviceip; ?>" target="top"><?php echo $device->deviceip; ?></a></b> <br/>
            <b>External Ip : <?php echo $device->visibleip; ?></b><br/>
            <b>Uuid : <?php echo $device->uuid; ?></b><br/>
            <b>Updated : <?php echo $device->updated; ?></b><br/>
        </div>

        <div class="col-sm-12 col-md-6">
            <small>Settings : <i><?php echo $device->setting; ?></i></small>
        </div>
    </div>

    <div class="row" style="background-color: lightgrey;">
     <br/>
    </div>

     <div class="row">
        <div class="col-sm-12 col-md-6">
            <a href="index.php?uuid=<?php echo $uuid; ?>&view=<?php echo TEMP_VIEW; ?>&timezone=<?php echo $timezone; ?>"> <b>Temperature &nbsp; &nbsp;<span class="glyphicon glyphicon-scale"></span></b>
            </a>
            <div class="embed-responsive  embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                    src="/views/graph/temp.php?uuid=<?php echo $uuid; ?>&timezone=<?php echo $timezone; ?>"></iframe>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <a href="index.php?uuid=<?php echo $uuid; ?>&view=<?php echo MOTION_VIEW; ?>&timezone=<?php echo $timezone; ?>"> <b><span class="glyphicon glyphicon-cog">Activity &nbsp; &nbsp;</span></b>
            </a>
            <div class="embed-responsive  embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                    src="/views/graph/motion.php?uuid=<?php echo $uuid; ?>&timezone=<?php echo $timezone; ?>"></iframe>
            </div>
        </div>
   </div>


     <div class="row" style="background-color: lightgrey;">
        <div class="col-sm-12 col-md-6" style="float: left;">
            <br/>
            <?php $share_name= $utils->getShareName($uuid); ?>
            <form class="form-horizontal" name=shareMotion method=GET action="sql_action.php">
                <label> Share Name : </label><input type="text" name="share_name" value="<?php echo $share_name; ?>" style="width: 6em; height: 2em;"/>
                <input type="hidden" name="device_name" value="<?php echo $device_name; ?>"/>
                <input type=hidden name=view value="<?php echo DEVICE_VIEW ?>" />
                <input type=hidden name=action value="ShareMotion" />
                <input type=hidden name=uuid value="<?php echo $device->uuid ?>" />
                <input type="hidden" name="box" value="<?php echo $device->box_name;?>" />
                <input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                <input type=hidden name=user_email value="<?php echo $user_email; ?>" />
                <input class="btn btn-sm btn-link" type="submit" type=submit name="submit" value="Share Motion" />
            </form>
            <?php if (strlen($share_name) > 1 ) {
                echo '<p> Share is https://app.ibeyonde.com/share.php?animate=true&id='.base64_encode($share_name."@".$uuid).'<br/>';
                echo '<br/><i>Share the above url to share the correspnding feed with your friends or on your website. You can change it anytime.</i>';
            }
            else {
                echo 'Not shared.<br/>';
                echo '</br><i>To start sharing fill in a share name in above form. Share name should have only characters and digits.</i>';
            }?>
        </div>
        <div class="col-sm-12 col-md-6" style="float: right;">
          <iframe class="embed-responsive-item" scrolling="no" frameborder="0" width="216" height="162" id="<?php echo $device->uuid; ?>0" style="display: block; float:right"
                src="../views/motion.php?&timezone=<?php echo $device->timezone; ?>&uuid=<?php echo $device->uuid; ?>&animate=true"> </iframe>
        </div>
     </div>


    <?php
    $row_count = count ( $utils->action ['left'] );

    for($i = 0; $i < $row_count; $i ++) {
        $action_name_left = $utils->action ['left'] [$i];
        $action_name_right = $utils->action ['right'] [$i];
        $action_message_left = $utils->action_message [$action_name_left];
        $action_message_right = $utils->action_message [$action_name_right];
        $action_icon_left = $utils->action_icon [$action_name_left];
        $action_icon_right = $utils->action_icon [$action_name_right];
        $action_popup_left = $utils->action_popup_message [$action_name_left];
        $action_popup_right = $utils->action_popup_message [$action_name_right];
        ?>
     <div class="row">
        <div class="col-sm-12 col-md-6">
            <form name="<?php echo $action_name_left; ?>" method=GET action="https://<?php echo $ip; ?>/udp/device_action.php"><input type=hidden name=server value="<?php echo $_SERVER['SERVER_NAME']; ?>" />
                <input type=hidden name=view value="<?php echo DEVICE_VIEW; ?>" /> <input type=hidden name=action value=<?php echo $action_name_left; ?> /> <input type=hidden name=uuid
                    value="<?php echo $device->uuid; ?>" /> <input type=hidden name=device_name value="<?php echo $device_name; ?>" /> <input type=hidden name=port value="<?php echo $port; ?>" /><input
                    type=hidden name=user_id value="<?php echo $user_id; ?>" /><input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                    <input type=hidden name=box value="<?php echo $device->box_name; ?>" /> <input type=hidden name=tk value="<?php echo $device->token; ?>" />
                    <input type=hidden name=timezone value="<?php echo $timezone; ?>" />

                        <?php if ($action_popup_left != 'None') { ?>
                        <div class="modal fade" id="<?php echo $action_name_left."Modal"; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $action_name_left."ModalLabel"; ?>">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title"><?php echo $action_message_left; ?></h4>
                            </div>
                            <div class="modal-body">
                                <p><?php echo $action_popup_left; ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success"><?php echo $action_name_left; ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" data-toggle="modal" data-target="<?php echo "#".$action_name_left."Modal"; ?>" class="btn btn-default btn-sm btn-block" name="<?php echo $action_name_left; ?>"
                    value="<?php echo $action_name_left; ?>">
                         <?php }  else { ?>
                    <button type="submit" class="btn btn-default btn-sm btn-block" name="<?php echo $action_name_left; ?>" value="<?php echo $action_name_left; ?>">
                       <?php } ?>
                        <span class="glyphicon <?php echo $action_icon_left; ?>">&nbsp;<?php echo $action_message_left; ?></span>
                    </button>

            </form>
        </div>
        <div class="col-sm-12 col-md-6">
            <form name="<?php echo $action_name_right; ?>" method=GET action="https://<?php echo $ip; ?>/udp/device_action.php"><input type=hidden name=server value="<?php echo $_SERVER['SERVER_NAME']; ?>" />
                <input type=hidden name=view value="<?php echo DEVICE_VIEW; ?>" /> <input type=hidden name=action value=<?php echo $action_name_right; ?> /> <input type=hidden name=uuid
                    value="<?php echo $device->uuid; ?>" /> <input type=hidden name=device_name value="<?php echo $device_name; ?>" /> <input type=hidden name=port value="<?php echo $port; ?>" /><input
                    type=hidden name=user_id value="<?php echo $user_id; ?>" /><input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                    <input type=hidden name=box value="<?php echo $device->box_name; ?>" /><input type=hidden name=tk value="<?php echo $device->token; ?>" />
                    <input type=hidden name=timezone value="<?php echo $timezone; ?>" />


                        <?php if ($action_popup_left != 'None') { ?>
                        <div class="modal fade" id='<?php echo $action_name_right."Modal"; ?>' tabindex="-1" role="dialog" aria-labelledby='<?php echo $action_name_right."ModalLabel"; ?>'>
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title"><?php echo $action_message_right; ?></h4>
                            </div>
                            <div class="modal-body">
                                <p><?php echo $action_popup_right; ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success"><?php echo $action_name_right; ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" data-toggle="modal" data-target="<?php echo "#".$action_name_right."Modal"; ?>" class="btn btn-default btn-sm btn-block" name="<?php echo $action_name_right; ?>"
                    value="<?php echo $action_name_right; ?>">
                         <?php } else { ?>
                    <button type="submit" class="btn btn-default btn-sm btn-block" name="<?php echo $action_name_right; ?>" value="<?php echo $action_name_right; ?>">
                        <?php } ?>
                        <span class="glyphicon <?php echo $action_icon_right; ?> ">&nbsp;<?php echo $action_message_right; ?></span>
                    </button>

            </form>
        </div>
    </div>
    <?php
    }
    ?>


    <?php
    if (strpos ( $device->capabilities, "SIP" ) !== false) {
        if (count ( $sips ) == 0) {
            ?>

    <div class="row">
        <form name=deviceEnableVoiceAction method=GET action="https://<?php echo $ip; ?>/udp/device_action.php"><input type=hidden name=server value="<?php echo $_SERVER['SERVER_NAME']; ?>" />
            <input type=hidden name=view value="<?php echo DEVICE_VIEW ?>" /> <input type=hidden name=action value="EnableVoice" /> <input type=hidden name=uuid value="<?php echo $device->uuid ?>" /> <input
                type=hidden name=device_name value="<?php echo $device_name; ?>" /> <input type=hidden name=port value="<?php echo $port; ?>" /><input type=hidden name=user_id
                value="<?php echo $user_id; ?>" /><input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                <input type=hidden name=box value="<?php echo $device->box_name; ?>" /><input type=hidden name=tk value="<?php echo $device->token; ?>" />
            <button type="submit" class="btn btn-default btn-sm btn-block btn-info" type=submit name="submit" value="EnableVoice">
                <span class="glyphicon glyphicon glyphicon-refresh">&nbsp;Enable Voice

            </button>
        </form>
    </div>

    <?php
        } else {
            ?>
    <div class="row">
        <form name=deviceManageVoiceAction method=GET action="https://<?php echo $ip; ?>/udp/device_action.php"><input type=hidden name=server value="<?php echo $_SERVER['SERVER_NAME']; ?>" />
            <input type=hidden name=view value="<?php echo DEVICE_VIEW ?>" /> <input type=hidden name=action value="ManageVoice" /> <input type=hidden name=uuid value="<?php echo $device->uuid ?>" /> <input
                type=hidden name=device_name value="<?php echo $device_name; ?>" /> <input type=hidden name=port value="<?php echo $port; ?>" /><input type=hidden name=user_id
                value="<?php echo $user_id; ?>" /><input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                <input type=hidden name=box value="<?php echo $device->box_name; ?>" /><input type=hidden name=tk value="<?php echo $device->token; ?>" />
            <button type="submit" class="btn btn-default btn-sm btn-block  btn-info" type=submit name="submit" value="ManageVoice">
                <span class="glyphicon glyphicon glyphicon-refresh">&nbsp;Manage Voice

            </button>
        </form>
    </div>
    <?php
        }
    }
    ?>

    <div class="row">
        <?php
        if ($user_name == "aprateek"){
            $dcert = new DeviceCert ();
            $device_cert = $dcert->loadDeviceCert ( $uuid );
            if (! $device_cert) {
                $passphrase = utils::randomString ( 8 );
                $cert = $utils->generateKeyPair ( $uuid, $user_name, $user_email, $passphrase );
                $device_cert = $dcert->saveDeviceCert ( $uuid, $cert ['public'], $cert ['private'], $cert ['passphrase'] );
            }
            ?>
            <div class="col-sm-2 col-md-4">
                <button type="button" class="btn btn-default btn-sm btn-block btn-info" data-toggle="collapse" data-target="#private">Private Key^</button>
                <div id="private" class="collapse"><br/><code style="white-space: pre-wrap;"><?php echo $device_cert->private; ?></code><br/></div>
            </div>
            <div class="col-sm-2 col-md-4">
                <form name=pubKey method=GET action="https://<?php echo $ip; ?>/udp/device_action.php"><input type=hidden name=server value="<?php echo $_SERVER['SERVER_NAME']; ?>" />
                    <input type=hidden name=view value="<?php echo DEVICE_VIEW ?>" /> <input type=hidden name=action value="PubKey" /> <input type=hidden name=uuid value="<?php echo $device->uuid ?>" /> <input
                        type=hidden name=device_name value="<?php echo $device_name; ?>" />
                    <input type=hidden name=port value="<?php echo $port; ?>" /><input type=hidden name=user_id value="<?php echo $user_id; ?>" /><input type=hidden name=user_name value="<?php echo $user_name; ?>" />
                        <input type=hidden name=box value="<?php echo $device->box_name; ?>" /><input type=hidden name=tk value="<?php echo $device->token; ?>" />
                    <button type="submit" class="btn btn-default btn-sm btn-block  btn-info" type=submit name="submit" value="PubKey">
                        <span class="glyphicon glyphicon glyphicon-refresh">&nbsp;Enable Public Key

                    </button>
                </form>
            </div>
            <div class="col-sm-2 col-md-4">
                <button type="button" class="btn btn-default btn-sm btn-block btn-info" data-toggle="collapse" data-target="#pass">Passphrase^</button>
                <div id="pass" class="collapse"><br/><code style="white-space: pre-wrap;"><?php echo $device_cert->passphrase; ?></code><br/></div>
            </div>
            <?php } ?>
    </div>

    <div class="row" style="background-color: lightgrey;">
     <br/>
    </div>

    <div class="row">
        <br /> <br />
        <br /> <br /> <br /> <br />
    </div>

</div>
<?php include('_footer.php'); ?>
