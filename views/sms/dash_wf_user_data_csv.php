<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
include_once(__ROOT__ . '/classes/wf/data/WfUserData.php');
include_once(__ROOT__ . '/classes/core/Log.php');

$_SESSION['log'] = new Log ("info");

$user_id = $_GET['user_id'];
$bot_id = $_GET['bot_id'];
$submit =  $_GET['submit'];
$name = str_replace(" ", "-", $_GET['bot_name']);

$kb = new WfUserData($user_id, $bot_id);

list($cols, $rows) = $kb->generateReport();
$header = $cols;
if ($submit == "csv"){
    $fp = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$name.'-UserData.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    array_unshift($header, "number");
    fputcsv($fp, $header);
   
    foreach($rows as $key=>$row) {
        $csvline= array();
        $csvline[] = $key;
        foreach($cols as $col) {
            $csvline[] = (array_key_exists($col, $row) ? $row[$col] : "");
        }
        fputcsv($fp, $csvline);
    } 
}

die;

?>
