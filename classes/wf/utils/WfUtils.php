<?php
require_once (__ROOT__ . '/classes/wf/actions/ActionChoice.php');
require_once(__ROOT__.'/classes/sms/SmsMinify.php');
require_once (__ROOT__ . '/classes/sms/SmsImages.php');
require_once (__ROOT__ . '/classes/wf/data/SmsContext.php');
require_once (__ROOT__ . '/classes/Utils.php');
include_once(__ROOT__ . '/classes/wf/data/WfUserForm.php');

class WfUtils {
    // Action Name=extract Desc=( ()) Extract=( ({{ex/email/string/email/}}))
    public static function checkAction($name_array, $desc_array, $extract_array, $state_array) {
        $log= isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        $initial_count = count ( $name_array );
        $actions_array = array ();
        $unmatched_added = false;
        $exclusive_choice = false; // choices/mchoices/extract are exclusive
        $exclusive_extract = false;
        for($i = 0; $i < count ( $name_array ); $i ++) {
            error_log ( "Action Name=" . $name_array [$i] . " Desc=" . SmsWfUtils::flatten ( $desc_array [$i] ) . " Extract=" . SmsWfUtils::flatten ( $extract_array [$i] ));
            if ($desc_array [$i] == "" && $name_array [$i] != "unmatched"  && $name_array [$i] != "extract") {
                // in unmactehd and extract there is not action desc, for extract pattern is delivered in extract_array
                $_SESSION ['message'] = "Bad action data, skipping.";
                $log->debug ( "Bad action data, skipping." );
                continue;
            }
            if ($state_array [$i] == "") {
                $_SESSION ['message'] = "No next state state, skipping.";
                $log->debug ( "No next state state, skipping." );
                continue;
            }
            if ($name_array [$i] == "delete") {
                $log->debug ( "Deleted" );
                // skip
                continue;
            }
            if (! $exclusive_choice && $name_array [$i] == "choices") {
                $choice_param = is_array ( $desc_array [$i] ) ? implode ( ",", $desc_array [$i] ) : $desc_array [$i];
                $actions_array [] = "action=choices&choices=" . $choice_param . "&extract=" . $extract_array [$i] . "&next_state=" . trim ( $state_array [$i] );
                $exclusive_choice = true;
            } else if (! $exclusive_extract && $name_array [$i] == "extract") {
                $actions_array [] = "action=extract&extract=" . $extract_array [$i] . "&next_state=" . trim ( $state_array [$i] );
                $exclusive_extract = true;
            } else if ($name_array [$i] == "intent") {
                $actions_array [] = "action=intent&intent=" . $desc_array [$i] . "&extract=" . $extract_array [$i] . "&next_state=" . trim ( $state_array [$i] );
            } else if ($name_array [$i] == "search") {
                $actions_array [] = "action=search&search=" . $desc_array [$i] . "&next_state=" . trim ( $state_array [$i] );
            } else if ($name_array [$i] == "upload") {
                $actions_array [] = "action=upload&upload=" . $desc_array [$i] . "&next_state=" . trim ( $state_array [$i] );
            } else if ($unmatched_added == false && $name_array [$i] == "unmatched") {
                $unmatched_added = true; // only one unmatched allowed
                $actions_array [] = "action=unmatched&next_state=" . trim ( $state_array [$i] );
            } else {
                $log->warn ( "UNKNOWN ACTION " . $name_array [$i] );
                $_SESSION['message'] = "WARNING: The action ".SmsWfUtils::flatten ($name_array[$i])." is not allowed";
            }
        }
        $actions = "";
        foreach ( $actions_array as $action ) {
            $actions .= $action . "\n";
        }
        $actions = trim ( $actions );
        
        if (strpos ( $actions, "action" ) !== 0) {
            $_SESSION ['message'] = "No Node added, bad action " . $actions;
            $log->warn ( "No Node added, bad action " . $actions );
            return null;
        } else {
            $actions_array = explode ( "\n", $actions );
            $final_count = count ( $actions_array );
            $log->debug ( "FINAL <<<< " . $actions );
            if ($initial_count != $final_count) {
                // throw new Exception("Action number mismatch");
                $log->debug ( "FATAL-----------------------" );
            }
            return $actions;
        }
    }
    
    // choices and (extract, intent, search) are mutually exclusive, choice is a dropdown; combine all extract, intents and search into 1 input
    // unmatchd is next
    public static function getDisplay($uid, $bid, $number, $action_array, $form_start) {
        $log= isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        $html = "";
        
        //check if there ia an upload
        foreach ( $action_array as $action ) {
            parse_str ( $action, $Laction );
            if ($Laction ['action'] == "upload" ){
                $html = $form_start;
                $html = $html . '<p><img class="img-fluid" src="about:blank" alt="" id="show-picture"></p>';
                $html = $html . '<p id="error"></p>';
                $html = $html . '<p><input type="file" id="take-picture" name="fileToUpload" accept="image/*"></p>';
                $html = $html . '<script src="/js/camera.js"></script>';
                $html = $html . '<button type="submit" class="form-control btn1" name="submit" value="upload" class="btn btn1">Upload</button></form>';
                return $html;
            }
        }
        
        
        $choice_flag = false;
        $button_required = false;
        // Render Choice
        foreach ( $action_array as $action ) {
            $label = "";
            parse_str ( $action, $Laction );
            $log->debug ( "Choice Action Str=" . print_r ( $Laction, true ) );
            if ($Laction ['action'] == "choices") {
                $html = $form_start. self::choices( $uid, $bid, $number, $Laction );
                $choice_flag = true;
                // if there is a select in choices then an additonal submit button is required
                $button_required = strpos($html, "select"); 
                break;
            }
        }
        
        $extract = false;
        // Render Choice
        if (! $choice_flag) { // Render intent, extract and search, only if there is no choice dropdown
            foreach ( $action_array as $action ) { // Render in put box if there are intents and extracts
                $label = "";
                parse_str ( $action, $Laction );
                //error_log ( "Intent/Extract Action Str=" . print_r ( $action, true ) );
                if ($Laction ['action'] == "extract") {
                    $html = $html . $form_start. '<input type=text  class="form-control btn1" name="extract"  value="" placeholder="..." required></input>';
                    $button_required = true;
                    $extract = true;
                    break;
                }
            }
            
            //if there is an extract then don;t put intents as these will interfere
            if (! $extract){
                foreach ( $action_array as $action ) { // Render in put box if there are intents and extracts
                    $label = "";
                    parse_str ( $action, $Laction );
                    //error_log ( "Intent/Extract Action Str=" . print_r ( $action, true ) );
                    if ($Laction ['action'] == "intent") {
                        $html = $html . $form_start. '<input type=text  class="form-control btn1" name="intent"  value="" placeholder="..." required></input>';
                        $button_required = true;
                        break;
                    }
                }
            }
        }
        
        foreach ( $action_array as $action ) { // Render all the searches
            $label = "";
            parse_str ( $action, $Laction );
            if ($Laction ['action'] == "unmatched") {
                $html = $html . $form_start;
                $button_required = true;
                break;
            }
        }
        
        if ($button_required == true) {
            if ($extract){
                $html = $html . '<button type="submit" class="form-control btn1" name="submit" value="-" class="btn btn1">Next</button></form>';
            }
            else {
                $html = $html . '<button type="submit" class="form-control btn1" name="submit" value="-" class="btn btn1">Next</button></form>';
            }
        }
        //If there are more than 2 searches then conver these to dropdowns
        //count number of searches
        $search_count=0;
        foreach ( $action_array as $action ) {
            if ($Laction ['action'] == "search" ){
                $search_count = $search_count + 1;
            }
        }
        
        if ($search_count > 3){// Render searches as DROPDOWN
            $html = $html . $form_start. '<select class="form-control" name="search" required>';
            foreach ( $action_array as $action ) { 
                $label = "";
                parse_str ( $action, $Laction );
                //error_log ( "Render search as dropdown Action Str=" . print_r ( $action, true ) );
                if ($Laction ['action'] == "search") {
                    $html = $html . '<option name="' . $Laction ['search'] . '">' . $Laction ['search'] . '</option>';
                }
            }
            $html = $html . '</select>';
            $html = $html . '<button type="submit" class="form-control btn1" name="submit" value="-" class="btn btn1">Next</button></form>';
        }
        else {
            foreach ( $action_array as $action ) { // Render all the searches 
                $label = "";
                parse_str ( $action, $Laction );
                //error_log ( "Render all search buttons Action Str=" . print_r ( $action, true ) );
                if ($Laction ['action'] == "search") {
                    $html = $html . $form_start.'<button type="submit" class="form-control" name="submit" value="' . $Laction ['search'] . '" 
                        class="form-control btn1">' . $Laction ['search'] . '</button></form>';
                } 
            }
        }
        return $html;
    }
    
    //DEPRICATED
    public static function choices($uid, $bid, $number, $Laction) {
        $log= isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        
        $choices = $Laction ["choices"];
        $Action = new ActionChoice ( $uid, $bid, $number );
        
        if (strpos ( $choices, "{{" ) === false) {
            // choice is comma separated tring
            $label = "Choose from";
            if (strpos ( $choices, "," ) === false) {
                $choices = array($choices);
            }
            else {
                $choices = explode ( ",", $choices );
            }
        } else {
            $pattern_array = $Action->parse_pattern_string ( $choices );
            $choices = array ();
            foreach ( $pattern_array as $item ) {
                if (is_array ( $item )) {
                    if ($item [0] == "db") {
                        // uid is first param
                        $db = new WfDb ( $uid, $bid, $number );
                        $vals = $db->executeWfDb ( $item );
                        $choices = array ();
                        foreach ( $vals as $val ) {
                            $choices [] = SmsWfUtils::join ( $val );
                        }
                        $label = "Choose " . $item [1];
                    } else if ($item [0] == "mydb") {
                        // uid is first param
                        $db = new WfMydb ( $uid, $bid, $number );
                        $choices = $db->execute ( $item );
                        $label = "Choose " . $item [1];
                    } else {
                        throw new Exception ( "Unknown query type " . SmsWfUtils::flatten ( $item ) );
                    }
                }
            }
        }
        $log->debug  ( "Choices Array= " . print_r ( $choices, true ) );
        
        $extract_str = $Laction ["extract"];
        
        // Get name of the item that is extracted
        $pattern_array = $Action->parse_pattern_string ( $extract_str );
        $extractions = array ();
        foreach ( $pattern_array as $item ) {
            if (is_array ( $item )) {
                $extractions [] = $item;
            }
        }
        $log->debug ( "Extractions = " . SmsWfUtils::flatten ( $extractions ) );
        // item 0 goes with the choice as name
        $item = array_shift ( $extractions );
        
        if (count($choices) < 3) {//IF there is 1 or 2 choices only then convert them to buttons, instead of dropdown
            foreach ( $choices as $choice ) {
                $html = $html . $form_start.'<button type="choice" class="form-control" name="submit" value="' . $choice .
                '" class="form-control btn1">' . $choice . '</button>';
            }
            $html = $html . '</form>';
        }
        else {
            $html = '<label><h9>' . $label . " (" . $item [1] . ")" . '</h9></label>';
            $html = $html . '<select class="form-control" name="choice" required>';
            foreach ( $choices as $choice ) {
                $html = $html . '<option name="' . $choice . '">' . $choice . '</option>';
            }
            $html = $html . '</select>';
            
            ///TODO REMOVE EITHER IT should be choice or extraction cannot be both
            // add any additional extractions as normal extracts
            $log->debug("Extractions = " . print_r($extractions, true));
            foreach ( $extractions as $extract ) {
                $log->debug("Extract = " . print_r($extract, true));
                $html = $html . "<label>" . $extract[1] . "</label>";
                $html = $html . '<input type=text  class="form-control btn1" name="' . $extract[1] . '"  value="-" placeholder="..." required></input>';
            }
            /// REMOVED
        }
        $log->trace( "HTML=" . $html );
        return $html;
    }
    public static function checkWorkflow() {
        return true;
    }
    
    // bot_id=66baba46621&user_id=95&there_number=9999999&item1=Kiwi+Green&quantity1=500gm&action=extract&state=order_summary
    // Enc=yXfGyqodtp, Str=sePzkAv/MzxZjrSaGnZJ2r/ST5DHOSfmYPOuMXoYC9dBmFo2+D73GY7gh/eck14E7kvO7zeEPedxvYkPN9pvD9wE1a97gQ77vRW8Yk0MAelPp+U4/PyQ==
    public static function _sms($get_array) {
        $log = isset ( $_SESSION ['log'] ) ? $_SESSION ['log'] : $GLOBALS ['log'];
        $log->debug ( SmsWfUtils::flatten ( $get_array ) );
        $ignore = array ("p","i","t","bot_id","user_id","there_number","action","state","next_state",
        );
        $sms = "";
        foreach ( $get_array as $name => $value ) {
            if (! in_array ( $name, $ignore ) && $value != "-") {
                $log->debug ( "Adding===" . $name . $value );
                if ($value=='Extract')continue;//this value inetrferes with the extraction
                if ($sms == "") {
                    $sms = $value;
                } else {
                    $sms = $sms . "," . $value;
                }
            }
        }
        $log->debug ( "SMS===" . $sms );
        return $sms;
    }
    
    public static function _css($type, $status){
        $css="";
        if ($type != null){
            if ($type == "blue") {
                $css .= $css. '<link rel="stylesheet" href="/css/wf_blue.css">';
            }
            else if ($type == "black") {
                $css .= $css.  '<link rel="stylesheet" href="/css/wf_black.css">';
            }
            else if ($type == "white") {
                $css .= $css.  '<link rel="stylesheet" href="/css/wf_white.css">';
            }
            else {
                $css .= $css.  '<link rel="stylesheet" href="/css/wf_light.css">';
            }
        }
        else {
            $css .= $css.  '<link rel="stylesheet" href="/css/wf_white.css">';
        }
        if ($status != null){
            $isMath = $status & 8;
            if ($isMath){
                $css .= $css.'<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>'.
                '<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>';
            }
        }
        return $css;
    }
    
    
    
    // choices and (extract, intent, search) are mutually exclusive, choice is a dropdown; combine all extract, intents and search into 1 input
    // unmatchd is next
    public static function getChatInputs($uid, $bid, $number, $action_array, $form_start) {
        $html = "";
        $choice_flag = false;
        $button_required = false;
        //check if there ia an upload
        foreach ( $action_array as $action ) {
            parse_str ( $action, $Laction );
            if ($Laction ['action'] == "upload" ){
                $html = $form_start;
                $html = $html . '<p><img class="img-fluid" src="about:blank" alt="" id="show-picture"></p>';
                $html = $html . '<p id="error"></p>';
                $html = $html . '<p><input type="file" id="take-picture" name="fileToUpload" accept="image/*"></p>';
                $html = $html . '<script src="/js/camera.js"></script>';
                $html = $html . '<button type="submit" class="form-control btn1" name="submit" value="upload" class="btn btn1">Upload</button></form>';
                return $html;
            }
        }
        
        // Render Choice
        foreach ( $action_array as $action ) { //choices will be multple buttons
            $label = "";
            parse_str ( $action, $Laction );
            //error_log ( "Choice Action Str=" . print_r ( $Laction, true ) );
            if ($Laction ['action'] == "choices") {
                $html = $html .$form_start . self::choicesButton( $uid, $bid, $number, $Laction );
                $choice_flag = true;
                break;
            }
        }
        
        $extract = false;
        // Render Choice
        if (! $choice_flag) { // Render intent, extract and search, only if there is no choice dropdown
            foreach ( $action_array as $action ) { // Render in put box if there are intents and extracts
                $label = "";
                parse_str ( $action, $Laction );
                //error_log ( "Intent/Extract Action Str=" . print_r ( $action, true ) );
                if ($Laction ['action'] == "extract") {
                    $html = $html . $form_start. '<input type=text name="Extract"  value="" placeholder="..." required></input>';
                    $button_required = true;
                    $extract = true;
                    break;
                }
            }
            
            //if there is an extract then don;t put intents as these will interfere
            if (! $extract){
                foreach ( $action_array as $action ) { // Render in put box if there are intents and extracts
                    $label = "";
                    parse_str ( $action, $Laction );
                    //error_log ( "Intent/Extract Action Str=" . print_r ( $action, true ) );
                    if ($Laction ['action'] == "intent") {
                        $html = $html . $form_start. '<input type=text  name="Intent"  value="" placeholder="..." required></input>';
                        $button_required = true;
                        break;
                    }
                }
            }
        }
        
        foreach ( $action_array as $action ) { // Render all the searches
            $label = "";
            parse_str ( $action, $Laction );
            if ($Laction ['action'] == "unmatched") {
                $html = $html . $form_start;
                $button_required = true;
                break;
            }
        }
        
        if ($button_required == true) {
            if ($extract){
                $html = $html . '<button type="submit" name="submit" value="Extract">Next</button></form>';
            }
            else {
                $html = $html . '<button type="submit" name="submit" value="Next">Next</button></form>';
            }
        }
        //If there are more than 2 searches then conver these to dropdowns
        //count number of searches
        $search_count=0;
        foreach ( $action_array as $action ) {
            if ($Laction ['action'] == "search" ){
                $search_count = $search_count + 1;
            }
        }
        
        foreach ( $action_array as $action ) { // Render all the searches
            $label = "";
            parse_str ( $action, $Laction );
            //error_log ( "Render all search buttons Action Str=" . print_r ( $action, true ) );
            if ($Laction ['action'] == "search") {
                $html = $html . $form_start.'<button type="submit" name="submit" value="' . $Laction ['search'] .
                    '">' . $Laction ['search'] . '</button></form>';
            }
        }
        return $html;
    }
    
    public static function choicesButton($uid, $bid, $number, $Laction) {
        $log= isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        
        $choices = $Laction ["choices"];
        $Action = new ActionChoice ( $uid, $bid, $number );
        
        if (strpos ( $choices, "{{" ) === false) {
            // choice is comma separated tring
            $label = "Choose from";
            if (strpos ( $choices, "," ) === false) {
                $choices = array($choices);
            }
            else {
                $choices = explode ( ",", $choices );
            }
        } else {
            $pattern_array = $Action->parse_pattern_string ( $choices );
            $choices = array ();
            foreach ( $pattern_array as $item ) {
                if (is_array ( $item )) {
                    if ($item [0] == "db") {
                        // uid is first param
                        $db = new WfDb ( $uid, $bid, $number );
                        $vals = $db->executeWfDb ( $item );
                        $choices = array ();
                        foreach ( $vals as $val ) {
                            $choices [] = SmsWfUtils::join ( $val );
                        }
                        $label = "Choose " . $item [1];
                    } else if ($item [0] == "mydb") {
                        // uid is first param
                        $db = new WfMydb ( $uid, $bid, $number );
                        $choices = $db->execute ( $item );
                        $label = "Choose " . $item [1];
                    } else {
                        throw new Exception ( "Unknown query type " . SmsWfUtils::flatten ( $item ) );
                    }
                }
            }
        }
        $log->debug ( "choicesButton Choices Array= " . print_r ( $choices, true ) );
        
        $extract_str = $Laction ["extract"];
        
        // Get name of the item that is extracted
        $pattern_array = $Action->parse_pattern_string ( $extract_str );
        $extractions = array ();
        foreach ( $pattern_array as $item ) {
            if (is_array ( $item )) {
                $extractions [] = $item;
            }
        }
        // item 0 goes with the choice as name
        $item = array_shift ( $extractions );
        
        foreach ( $choices as $choice ) {
            // add any additional extractions as normal extracts
            $log->debug ( "choicesButton Extractions = " . SmsWfUtils::flatten ( $extractions ) );
            foreach ( $extractions as $extract ) {
                $log->debug ( "choicesButton Extract = " . SmsWfUtils::flatten ( $extract ) );
                $html = '<input type=hidden  name="' . $extract_name [1] . '"  value=""></input>';
            }
            $html = $html . '<button type="submit" name="submit" value="' . $choice . '">' . $choice . '</button>';
        }
        $html = $html. "</form>";
        
        $log->trace ( "HTML=" . $html );
        return $html;
    }
    
    public static function upload($user_id, $bot_id, $file, $t, $size=1000000){
        $error="";
        if ($file["fileToUpload"]["error"] != 0){
            error_log(' File upload failed for '.$file["fileToUpload"]["name"] . ' with error ' . $file["fileToUpload"]["error"] . ' size is ' . $file["fileToUpload"]["size"]);
            $error=" Unknown error during upload !";
        }
        if ($file["fileToUpload"]["type"] != 'image/jpeg' && $file["fileToUpload"]["type"] != 'image/png' && $file["fileToUpload"]["type"] != 'image/jpg' ){
            error_log(' - bad format for '.$file["fileToUpload"]["name"] . ' with error ' .$file["fileToUpload"]["type"]);
            $error=$error." Illegal Format, Use jpg/jpeg/png ";
        }
        
        if ($file["fileToUpload"]["size"] > $size) {
            error_log('File is too large for '.$file["fileToUpload"]["name"] . ' with error ' . $file["fileToUpload"]["size"]);
            $error=$error." File size of less than 1MB is allowed ";
        }
        if ($bot_id == null){
            $error=$error." Authorization error ";
        }
        if ($error == null){
            $SU = new SmsImages();
            $filename = $file["fileToUpload"]["tmp_name"];
            error_log("Upload file=".$file["fileToUpload"]["name"]);
            $ext = pathinfo($file["fileToUpload"]["name"], PATHINFO_EXTENSION);
            $SU->uploadFileToSimOnline($bot_id."/".$t."/img/picture.".$ext, $filename);
            //save it to user data
            $Udata = new WfUserData($user_id, $bot_id);
            $Udata->saveUserData($t, "picture", "https://s3.ap-south-1.amazonaws.com/data.simonline/".$bot_id."/".$t."/img/picture.".$ext);
            //just move to next state
            return null;
        }
        else {
            error_log("FATAL:".$error);
            return $error;
        }
    }
    
    public static function generateForm($user_id, $tabella, $type){
        $log= isset($_SESSION['log']) ? $_SESSION['log'] : $GLOBALS['log'];
        $kb = new WfUserForm($user_id);  
        $log->trace("Tables" . SmsWfUtils::flatten($kb->ls()));
        
        $form="";
        $form .= '<!-- FORM START -->';
        $form .= '<script>';
        $form .= '$(function () {';
        $form .= '$("#targetForm").on("submit", function (e) {';
        $form .= 'e.preventDefault(),';
                $form .= '$.ajax({';
                $form .= 'type: "get",';
                    $form .= 'url: "/api/sql.php",';
                    $form .= 'data: $("form").serialize(),';
                    $form .= 'success: function (response) {';
                        $form .= 'if (response.trim() == ""){';
                        $form .= '$("#info-message").text("Successful ! If required we will get back to you !");';
                        $form .= '} else {';
                        $form .= '$("#info-message").text(response.replace(/(<([^>]+)>)/gi, ""));';
                        $form .= '}}});}); });</script>';
        
        $form .= '<form id="targetForm" onsubmit="return false;">';
        $form .= '<input type="hidden" name="username" value="justtest">';
        $form .= '<input type="hidden" name="created_at" value="#DATE_TIME">';
        $form .= '<input type="hidden" name="table" value="'.$tabella.'">';
        foreach($kb->t_columns_types($tabella) as $col_type=>$type){
            list($col, $type) = explode("->", $col_type);
            $form .= '<div class="form-group">';
            $form .= '<label id="label" style="display: none;">'. ucfirst($col).'</label>';
            $form .= '<input class="form-control" type="'.$type.'" name="'.$col.'" placeholder="'.$col.'" required>';
            $form .= '</div>';
        }
        $form .= '<div class="form-group">';
        $form .= '<button type="submit" name="submit" value="customise_add" class="btn">Submit</button>';
        $form .= '</div>';
        $form .= '</form>';
        $form .= '<h4 id="info-message"></h4>';
        $form .= '<!-- FORM END -->';
        return $form;
    }
    
}

?>