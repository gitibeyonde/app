<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
include_once(__ROOT__.'/classes/chat_ai/Rake.php');
include_once(__ROOT__.'/classes/chat_ai/verbs.php');
include_once(__ROOT__.'/classes/chat_ai/nouns.php');

$content = file_get_contents("/Users/aprateek/work/tmp/bot_data/wiki/WikiQA-dev.tsv");

$word_array = array();

$ca = explode("\n", $content);
$rake = new Rake();

foreach ($ca as $entry){
    $parts = explode("\t", $entry);
    $Qrake = $rake->get_tags($parts[1]);
    $Arake = $rake->get_tags($parts[5]);
    
    if (count($Arake) <= count($Qrake))continue;
    
    $tp=0;
    $count=0;
    $continue = true;
    foreach ($Qrake as $qp){
        foreach ($Arake as $ap){
            $percent =0;
            if (in_array($qp, Verbs::$verbs)){
                //error_log("Verb=".$qp);
                if (strpos($ap, $qp) !== false){
                    $percent = 100;
                }
            }
            else if (in_array($qp, Nouns::$nouns)){
                //error_log("Noun=".$qp);
                if (strpos($ap, $qp) !== false){
                    $percent = 100;
                }
            }
            else {
                similar_text($qp,$ap, $percent);
            }
            $tp += $percent;
            $count++;
        }
        if (!$continue)break;
    }
    if ($count!=0){
    $tp = $tp/$count;
    if ($tp>40){
            error_log($parts[1]."--". $parts[5].">>>".SmsWfUtils::flatten($Qrake).">>>".SmsWfUtils::flatten($Arake)."==".$tp.",".$parts[6]);
        }
    }
}






?>