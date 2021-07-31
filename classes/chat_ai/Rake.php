<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
include_once(__ROOT__.'/classes/chat_ai/stop_words.php');
include_once(__ROOT__.'/classes/chat_ai/verbs.php');
include_once(__ROOT__.'/classes/chat_ai/nouns.php');
include_once(__ROOT__.'/classes/wf/SmsWfUtils.php');


class Rake
{
    private $regexp_stpwd=null;
    private $regexp_verbs=null;
    private $regexp_nouns=null;
    
    function __construct()
    {
        $this->regexp_stpwd =  $this->build_regex(StopWords::$stop_words);
        $this->regexp_verbs =  $this->build_regex(Verbs::$verbs);
        $this->regexp_nouns =  $this->build_regex(Nouns::$nouns);
    }
    
    function get_tags($sentence){
        $phrases_arr= array();
        $sentence = preg_replace($this->regexp_stpwd, '|', $sentence);
        //$sentence = preg_replace($this->regexp_verbs, '|', $sentence);
        //$sentence = preg_replace($this->regexp_nouns, '|', $sentence);
        $phrases = explode('|', $sentence);
        
        foreach ($phrases as $p)
        {
            $p = strtolower(trim($p));
            $p = preg_replace("/[^a-zA-Z0-9\s]/", "", $p);
            if ($p != '') array_push($phrases_arr, $p);
        }
        return $phrases_arr;
        //error_log("Score=".print_r($this->get_scores($phrases_arr), true));
    }
    
    
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
            $words = $this->split_phrase($p);
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
    
}

?>