<?php 



define('__ROOT__', dirname(dirname(dirname(__FILE__))));

echo __ROOT__;

include(__ROOT__.'/classes/agent_ai/stop_words.php');
include(__ROOT__.'/classes/agent_ai/verbs.php');
include(__ROOT__.'/classes/agent_ai/nouns.php');


$s1 = "Apollo Creed is a fictional character from the Rocky films , initially portrayed as the Undisputed Heavyweight Champion of the World.".
        "Creed had multiple nicknames , including The Master of Disaster , The King of Sting , The Dancing Destroyer , The Prince of Punch , ".
"The One and Only and The Count of Monte Fisto. Urban legend states that Apollo Creed 's name is a wordplay on the Apostles ' Creed , a statement of belief used in Christian churches .";

$s1 = "What is Apollo Creed ?";
$q2 = "What is King of Sting ?";

$s1 = strtolower(preg_replace('/\s+,\./', ' ', $s1));
$s1 = preg_replace("/[^a-zA-Z0-9\s]/", "", $s1);

$regexp_stpwd =  build_regex($stop_words);

$regexp_verbs =  build_regex($verbs);
$regexp_nouns =  build_regex($nouns);

$phrases_arr= array();
$phrases_temp = preg_replace($regexp_stpwd, '|', $s1);
$phrases_temp = preg_replace($regexp_verbs, '|', $phrases_temp);
$phrases_temp = preg_replace($regexp_nouns, '|', $phrases_temp);

$phrases = explode('|', $phrases_temp);

foreach ($phrases as $p)
{
    error_log($p);
    $p = strtolower(trim($p));
    if ($p != '') array_push($phrases_arr, $p);
}

error_log(print_r($phrases_arr, true));

error_log("Score=".print_r(get_scores($phrases_arr), true));


function split_phrase($phrase)
{
    $words_temp = str_word_count($phrase, 1, '0123456789');
    $words = array();
    
    foreach ($words_temp as $w)
    {
        if ($w != '' and !(is_numeric($w)))
        {
            array_push($words, $w);
        }
    }
    
    return $words;
}

function get_scores($phrases)
{
    $frequencies = array();
    $degrees = array();
    
    foreach ($phrases as $p)
    {
        $words = split_phrase($p);
        $words_count = count($words);
        $words_degree = $words_count - 1;
        
        foreach ($words as $w)
        {
            $frequencies[$w] = (isset($frequencies[$w]))? $frequencies[$w] : 0;
            $frequencies[$w] += 1;
            $degrees[$w] = (isset($degrees[$w]))? $degrees[$w] : 0;
            $degrees[$w] += $words_degree;
        }
    }
    
    foreach ($frequencies as $word => $freq)
    {
        $degrees[$word] += $freq;
    }
    
    $scores = array();
    
    foreach ($frequencies as $word => $freq)
    {
        $scores[$word] = (isset($scores[$word]))? $scores[$word] : 0;
        $scores[$word] = $degrees[$word] / (float) $freq;
    }
    
    return $scores;
}

function build_regex($stop_words)
{
    
    $stopwords_regex_arr = array();
    
    foreach ($stop_words as $word)
    {
        array_push($stopwords_regex_arr, '\b'. $word. '\b');
    }
    
    return  '/'. implode('|', $stopwords_regex_arr). '/i';
}


?>