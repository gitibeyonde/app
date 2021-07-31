<?php
require_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
require_once (__ROOT__ . '/classes/wf/data/WfData.php');
require_once (__ROOT__ . '/classes/sms/SmsImages.php');
include_once(__ROOT__ . '/classes/wf/data/WfUserForm.php');


class WfEdit {

    const EX_TYPE=array('string', 'number', 'integer', 'decimal', 'datetime', 'date');
    const EX_VALID=array('name', 'email', 'text', 'appointment', 'age');

    public static function getUserExtracts($bid, $node){
        $WFDB = new WfMasterDb();
        $Lnodes = $WFDB->getNodes($bid);
        $extract = array();
        foreach($Lnodes as $Lnode){
            if (strpos($Lnode['actions'], "extract") !== false){
                parse_str($Lnode['actions'], $nvs);
                $ext = $nvs['extract'];
                //error_log("Extract String ". print_r($ext, true));
                $patterns = explode(",", $ext);
                //error_log("Pat ". print_r($patterns, true));
                foreach ($patterns as $ex){
                    //error_log("Ex ". print_r($ex, true));
                    if (preg_match_all("/{{(.*?)}}/", $ex, $m)) {
                        //error_log("M ". print_r($m, true));
                        $fields=explode("/", $m[1][0]);
                        //error_log ("Key=".print_r($fields, true));
                        if (count($fields) != 5){
                            error_log("FATAL: Bad expression ".$m[2][0]. ", in ".$Lnode['actions'] ." Expression syntax is <op>/<f1>/<f2>/<f3>/");
                        }
                        $key = $fields[1];
                        //error_log ("Key=".print_r($key, true));
                        if (!array_key_exists($key, $extract)){
                            $extract[$key] = $fields;
                        }
                    }
                }
            }
        }
        //error_log(print_r($extract, true));
        return $extract;
    }


    public static function getBotKBStructure($uid, $bid){
        $KB = new WfData($uid, $bid);
        $Lkb = $KB->ls();

        $bot_kb = array();
        foreach ($Lkb as $kb){
            $cols = $KB->t_columns($kb);
            $bot_kb[$kb] = $cols;
        }
        //error_log(print_r($bot_kb, true));
        return $bot_kb;
    }

    public static function getExtractionString($name, $type, $validate){
        return "{{ex/$name/$type/$validate/}}";
    }

    public static function getImageList($bid){
        $Im = new SmsImages();
        return $Im->listImages($bid);
    }

    public static function getForms($user_id){
        $kb = new WfUserForm($user_id);
        return $kb->ls();
    }

    public static function getWorkflowExtractionsString(array $nodes){
        $extracts = array();
        foreach($nodes as $node){
            //look for {{ex/dish6/string/text/}}
            if (preg_match_all("/{{ex(.*?)}}/", $node['actions'], $m)) {
                foreach ($m[1] as $pattern) {
                    $fields = explode("/", $pattern);
                    $val = array($fields[2], $fields[3]);
                    $extracts[$fields[1]] = $val;
                }
            }
        }
        return $extracts;
    }

}

?>