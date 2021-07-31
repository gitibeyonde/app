<?php
define ( '__ROOT__',  dirname (dirname ( __FILE__ )));

require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
include_once(__ROOT__ .'/classes/core/Mysql.php');
include_once(__ROOT__ .'/classes/core/Log.php');
include_once (__ROOT__ . '/classes/sms/AwsStore.php');

$_SESSION['log'] = $log = new Log("info");

$username = isset($_POST["username"]) ? $_POST["username"] : (isset($_GET["username"]) ? $_GET["username"] : null);
if (!isset($username)){
    echo "Username not set";
    die;
}

$folder = isset($_POST["folder"]) ? $_POST["folder"] : (isset($_GET["folder"]) ? $_GET["folder"] : "/");

$mysql = new Mysql();
$user_id=$mysql->selectOne(sprintf("select user_id from users where user_name='%s'", $username));

if (!isset($user_id)){
    echo "Bad or missing username";
    exit;
}


if ($_SERVER['SERVER_NAME'] != "127.0.0.1"){

    $rk = trim(dns_get_record("deltacatalog.".$_SERVER['SERVER_NAME'], DNS_TXT)[0]["txt"]);
    //API key
    $SU = new SmsUtils();
    list($hk, $exp) = $SU->getHostKey($user_id);
    
    if ($rk != $hk){
        echo "Host key does not match ";
        exit;
    }
    
    $now = date(DateTime::ATOM);
    if ( $exp < $now){
        echo "Host key expired";
        exit;
    }
}

$Aws = new AwsStore ();
$filename = $_FILES ["fileToUpload"] ["tmp_name"];
if (isset($filename)){
    $r = $Aws->uploadUserFileToSimOnline ( $user_id, $_FILES, $folder );
    echo "Uploaded = " . $r;
}
else {
    echo "File upload failed !";
}
?>