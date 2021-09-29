<style>
    #alert_placeholder{
        position: fixed;
        width: 40%;
        right: 0;
        height: 20px;
        z-index: 999;
        opacity: 0.7;
    }
</style>

<script src="js/master.js"></script>
<script>
    $(document).ready(function(){
       var message = getUrlParameter('message');
        console.log(message);

        if(message !== "" && message !== undefined){
            showalert(message, "alert-success");
        }

        function showalert(message,alerttype) {
    $('#alert_placeholder').append('<div id="alertdiv" class="alert ' +  alerttype + '"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>')

    setTimeout(function() {
      $("#alertdiv").remove();

    }, 5000);
  }

    });




</script>

<b>Device Management</b>
<div id = "alert_placeholder"></div>
    <div class="card bg-faded box-shadow">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                <font size=2>Version : <?php echo $device->version ?></font>
            </div>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                <font size=2>Local Url : <a href="http://<?php echo $device->deviceip; ?>" target="top"><?php echo $device->deviceip; ?></a></font>
            </div>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                <font size=2>External Ip : <?php echo $device->visibleip; ?></font>
            </div>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                <font size=2>Uuid : <?php echo $device->uuid; ?></font>
            </div>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                <font size=2>Updated : <?php echo $settings['time']; ?></font>
            </div>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                <font size=2>Timezone : <?php echo $settings['timezone']; ?></font>
            </div>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                <font size=2>Public Key : <?php echo $settings['public_key']; ?></font>
            </div>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                <font size=2>Sip Registered : <?php echo $settings['sip_reg']; ?></font>
            </div>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                <font size=2>Video Mode : <?php echo $settings['video_mode']; ?></font>
            </div>
            <div class="col-md-8 col-sm-12 col-12 col-lg-8">
                <font size=2>Git Commit: <?php echo $settings['git_commit']; ?></font>
            </div>
            <div class="col-md-8 col-sm-12 col-12 col-lg-8">
                <font size=2>Uptime: <?php echo $settings['uptime']; ?></font>
            </div>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                <?php if (strpos($device->capabilities, "BELL") !== false) { ?>
                <a href="index.php?timezone=<?php echo $timezone; ?>&uuid=<?php echo $uuid; ?>&device_name=<?php echo $device_name;
                         ?>&view=<?php echo SIP_VIEW; ?>&box=<?php echo $box; ?>&tk=<?php echo $token;
                         ?>&local=<?php if (strcmp($device->visibleip, $remoteip) == 0 ) {
                             echo $device->deviceip; } else { echo "None";
                                                            }?>&loc=<?php echo DEVICE_SETTING; ?>">
                    <button type="button" class="btn btn-link">
                        <span class="glyphicon  glyphicon-bell">&nbsp;Setup Callable Accounts</span>
                    </button>
                </a>
                <?php } ?>
            </div>
        </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->restart, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->remove, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->reset, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->update, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->settings, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->buzz, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
</div>

<br/>
<b>Camera Tuning</b>
<div class="card bg-faded">
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
            <font size=2>Zoom : <?php echo $settings['zoom']; ?></font>
        </div>
        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
            <font size=2>Rotate : <?php echo $settings['rotate']; ?></font>
        </div>
        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
            <font size=2>Vertical Flip : <?php echo $settings['vertical_flip']; ?></font>
        </div>
        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
            <font size=2>Horizontal Flip : <?php echo $settings['horizontal_flip']; ?></font>
        </div>
        <div class="col-md-4 col-sm-6 col-12 col-lg-4">
            <font size=2>Brightness : <?php echo $settings['brightness']; ?></font>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->rotate, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->vflip, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->hflip, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->incbrt, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->decbrt, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
    <div class="col-md-4 col-sm-6 col-12 col-lg-4">
        <?php echo getButton($utils->snap, $ip, $device->uuid, $device->device_name, $port, $user_id, $user_name, $box, $timezone, $device->token, $role, $local, "device"); ?>
    </div>
</div>
