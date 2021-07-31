<?php
define ( '__ROOT__',  dirname (dirname ( __FILE__ )));

include_once (__ROOT__ . '/classes/core/SqliteCrud.php');
require_once (__ROOT__ . '/classes/sms/SmsUtils.php');
include_once(__ROOT__ .'/classes/core/Mysql.php');
include_once(__ROOT__ .'/classes/core/Log.php');
include_once (__ROOT__ . '/classes/sms/AwsStore.php');

$_SESSION['log'] = new Log("info");

foreach (array_keys($_GET) as $gk){
    if ($gk == "username") {
        $username = $_GET[$gk];
    }
    else if ($gk == 'table'){
        $table = $_GET[$gk];
    }
    else if ($gk == 'folder'){
        $folder = $_GET[$gk];
    }
    else{
        if ($_GET[$gk] == "#DATE_TIME"){
            $val = date(DateTime::ATOM);
        }
        else if ($_GET[$gk] == "#TIME_STAMP"){
            $val = time();
        }
        else {
            $val =  $_GET[$gk];
        }
        $map[$gk] = $val;
    }
}

foreach (array_keys($_POST) as $gk){
    if ($gk == "username") {
        $username = $_POST[$gk];
    }
    else if ($gk == 'table'){
        $table = $_POST[$gk];
    }
    else if ($gk == 'folder'){
        $folder = $_POST[$gk];
    }
    else{
        if ($_POST[$gk] == "#DATE_TIME"){
            $val = date(DateTime::ATOM);
        }
        else if ($_POST[$gk] == "#TIME_STAMP"){
            $val = time();
        }
        else {
            $val =  $_POST[$gk];
        }
        $map[$gk] = $val;
    }
}

if (!isset($username)){
    echo "Username not set";
    die;
}

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
    if (!isset($folder)){
        $folder = "/";
    }
    $r = $Aws->uploadUserFileToSimOnline ( $user_id, $_FILES, $folder );
    echo "Uploaded = " . $r;
}

//substitute file name
foreach ($map as $key=>$val){
    if ($val == "#FILE_NAME"){
        $map[$key] = $r;
    }
}

$Sql = new SqliteCrud ($user_id);
$r = $Sql->insert_map($table, $map);
if (! $r) {
    echo "Insert failed";
}

?>