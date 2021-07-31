<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
include_once(__ROOT__ . '/classes/wf/data/WfData.php');
include_once(__ROOT__ . '/classes/core/Log.php');

$_SESSION['log'] = new Log ("info");

$user_id = $_GET['user_id'];
$bot_id = $_GET['bot_id'];
$table = $_GET['tabella'];
$submit = $_GET['submit'];

$kb = new WfData($user_id, $bot_id);

if ($submit == "csv"){
    $fp = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$table.'-BotKB.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $header = $kb->t_columns($table);
    error_log("Header=".print_r($header, true));
    fputcsv($fp, $header);
    
    $rows = $kb->t_data_no_row_id($table);
    foreach($rows as $key=>$row) {
        error_log("Rows=".print_r($row, true));
        fputcsv($fp, $row);
    }
}

die;

?>
