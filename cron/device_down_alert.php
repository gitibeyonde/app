<?php

define ( '__ROOT__', dirname ( dirname ( __FILE__ ) ) );
// include the config
require_once (__ROOT__ . '/config/config.php');
require_once (__ROOT__ . '/classes/Device.php');
require_once (__ROOT__ . '/classes/NetUtils.php');
require_once (__ROOT__ . '/classes/AlertConfig.php');
require_once (__ROOT__ . '/classes/RegistryPort.php');
require_once (__ROOT__ . '/classes/AlertRaised.php');

$acs = AlertConfig::loadDeviceWithPingCheck();
$pr = new RegistryPort();
$ar = new AlertRaised();

$datetime = date('m/d/Y h:i:s a', time());
//error_log("$datetime ".time());
foreach ($acs as &$ac) {
    $user_name = Device::getDeviceOwner($ac->uuid);
    list ($ip, $port) = $pr->getIpAndPort($ac->uuid);
    $client = new NetUtils ( $user_name, $ac->uuid, $port);
    $client->register ();
    $time_delta = $client->getTimeDeltaForLastPing ();
    if ($time_delta > $ac->ping){
        error_log("$time_delta Raise an alert for ".$ac->uuid);
        $ar->notifyDeviceOffline($ac->uuid, $time_delta, $datetime);
    }
    else {
        error_log("$time_delta Device online ".$ac->uuid);
    }
}

?>