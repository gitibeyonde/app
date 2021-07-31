
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
        <div class="col-md-8 col-sm-12 col-12 col-lg-8">
            <font size=2>Git Commit: <?php echo $settings['git_commit']; ?></font>
        </div>
        <div class="col-md-8 col-sm-12 col-12 col-lg-8">
            <font size=2>Uptime: <?php echo $settings['uptime']; ?></font>
        </div>
    </div>
</div>

<div class="card bg-faded box-shadow">
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
</div>
