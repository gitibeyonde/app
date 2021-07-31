<?php 
define ( '__ROOT__',  dirname (dirname ( __FILE__ )));

require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/classes/Aws.php');

$aws = new Aws();
$uuid=$_GET['uuid'];
$key=$_GET['key'];
$recordings = $aws->deleteRecording($uuid, $key);
?>