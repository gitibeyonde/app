<?php

define ( '__ROOT__',  dirname ( __FILE__ ));

// include the config
require_once(__ROOT__.'/config/config.php');

// check for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once(__ROOT__.'/libraries/password_compatibility_library.php');
}

// load the login class
require_once(__ROOT__.'/classes/Login.php');
require_once(__ROOT__.'/classes/utils/Mobile_detect.php');

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();

if (isset($_POST['view'])){
    unset($_GET['logout']);
    $login->setView($_POST['view']);
}
else if (isset($_GET['view'])) {
    unset($_GET['logout']);
    $login->setView($_GET['view']);
}

if  ($login->getView() == MAIN_VIEW){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/main_view.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == FORUM_LISTING){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/forum/forum_listing.php");
    }
    else {
        include("views/forum/forum_readonly.php");
    }
}
else if  ($login->getView() == FORUM_CREATE_TOPIC){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/forum/forum_create.php");
    }
    else {
        include("views/forum/forum_readonly.php");
    }
}
else if  ($login->getView() == FORUM_TOPIC){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/forum/forum_topic.php");
    }
    else {
        include("views/forum/forum_readonly_topic.php");
    }
}
else if  ($login->getView() == MOTION_DASH){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/main_view.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == TEMP_DASH){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/temp_dash.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == SETTINGS_DASH){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/settings_dash.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == DEVICE_VIEW){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/device.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == DEVICE_SETTING){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/device_setting.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == DEVICE_SHARE){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/device_share.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == DEVICE_GRAPH){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/device_graph.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == DEVICE_PLACEMENT){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/device_placement.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ALERT_CONFIG){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/alert_config.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ALERT_DEVICE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/alert_device.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == TEMP_VIEW){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/graph/temp.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == MOTION_VIEW){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/graph/motion.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == IMAGE_PARAMS_VIEW){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/graph/image.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ANALYTICS){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/analytics.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == LIVE_VIEW){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') == true) {
            $login->errors[0] = "iPhone";
            include("views/live_player.php");
        }
        else if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') == true) {
            $login->errors[0] = "iPad";
            include("views/live_player.php");
        }
        else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == true) {
            $login->errors[0] = "Internet Explorer: Live dashboard does not work in your browser, install Firefox or Chrome !";
            include("views/live_jsplayer.php");
        }
        else if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') == true) {
            $login->errors[0] = "Firefox";
            include("views/live_player.php");
        }
        else if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') == true) {
            $login->errors[0] = "Chrome";
            include("views/live_player.php");
        }
        else if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') == true) {
            $login->errors[0] = "Safari";
            include("views/live_player.php");
        }
        else {
            $login->errors[0] = $_SERVER['HTTP_USER_AGENT'];
            include("views/live_player.php");
        }
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == LIVE_DASH){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == true) {
            $login->errors[0] = "Internet Explorer: Live dashboard does not work in your browser, install Firefox or Chrome !";
            include("views/live_jsdash.php");
        }
        else if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') == true || strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') == true || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') == true) {
            $login->errors[0] = "Mobile";
            include("views/live_dash.php");
        }
        else if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') == true) {
            $login->errors[0] = "Firefox";
            include("views/live_dash.php");
        }
        else if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') == true) {
            $login->errors[0] = "Chrome";
            include("views/live_dash.php");
        }
        else if  ( strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') == true) {
            $login->errors[0] = "Safari";
            include("views/live_dash.php");
        }
        else {
            $login->errors[0] = $_SERVER['HTTP_USER_AGENT'];
            include("views/live_dash.php");
        }
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == VIDEO_VIEW){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/livev_player.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == LIVE_SNAP){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("udp/snap.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == HISTORY_VIEW){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/history.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == PLAY_HISTORY){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/history_player.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == HISTORY_TAG){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/history_tag.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == FOR_IMAGE){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/history_plain.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == FOR_IMAGE_HEADER){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/history_header.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == RECORD_PLAY){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/record_player.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == RECORD_MP4PLAY){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/record_mp4play.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == RECORD_MANAGE){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/record_manage.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == SIP_VIEW){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sip.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == DEVICE_ALERTS){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/device_alert.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ALERT_DASH){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/alert_dash.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == TAGS){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/tags.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == DEVICE_TAGS){
    if (isset($_GET['uuid']) && isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/tags_device.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == USAGE_DASH){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/usage.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == USAGE_DETAILS){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name']) && isset($_GET['uuid'])){
        include("views/usage_details.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == USER_HOST_KEY){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/user_host_key.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == SMS_AUDIENCE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/messaging_audience.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == AUDIENCE_DB_TABLE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/messaging_audience_table.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == SMS_MESSAGE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/messaging_menu.php");
    }
    else {
        include("login.php");
    }
}

else if  ($login->getView() == MESSAGE_TEMPLATE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/messaging_template.php");
    }
    else {
        include("login.php");
    }
}

else if  ($login->getView() == MESSAGE_TEMPLATE_SMS){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/messaging_template_sms.php");
    }
    else {
        include("login.php");
    }
}

else if  ($login->getView() == MESSAGE_TEMPLATE_EMAIL){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/messaging_template_email.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == SMS_TRIGGER){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/messaging_trigger.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == SMS_SURVEY){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/messaging_survey.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == PAYMENT_SETUP){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/dash_wf_payment.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == DELIVER_MICROAPP){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/messaging_microapp.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == URL_MINIFY){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/url_minify.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == SMS_DETAIL){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/main_detail.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ADD_NUMBER){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/main_number.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ASSIGN_NUMBER){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wf_assign_number.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == SMS_PAY){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/sms_buy.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == SMS_COMPOSE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/main_compose.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == USER_ACCOUNT){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/user_account.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == USER_USAGE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/user_usage.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == USER_BILLING){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/user_billing.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == USER_CHARGE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/user_charge.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ADMIN_INTENT){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/admin/intent.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ADMIN_WORKFLOW){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/admin/workflow.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ADMIN_WF_NODES){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/admin/wf_nodes.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ADMIN_WF_EDITOR){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/admin/wf_editor.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ADMIN_WF_DATA){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/admin/wf_data.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == ADMIN_CHATDB){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/admin/chat_database.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WORKFLOW_LISTING){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/main_view.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WORKFLOW_NODE || $login->getView() == WORKFLOW_ACTION || $login->getView() == WORKFLOW_HELP){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_node.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WORKFLOW_CONNECT){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/agent_wf_connect.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WORKFLOW_MESSAGES){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/dash_message_log.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WORKFLOW_PLAYER){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/agent_wf_player.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == USER_DATA){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/dash_user_data.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == USER_REPORT){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/dash_user_data_report.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == USER_DATA_TABLE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/dash_user_data_table.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == MOBILE_USER_DATA){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/dash_mobile_data_view.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == MOBILE_USER_SCANNER){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/mobile_data_scanner.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == MOBILE_KB){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/mobile_kb.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_CATEGORY){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_category.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_DEMO){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_category_demo.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_DESC){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_header.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_LOGO){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_logo.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_IMAGES){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_body_images.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_KB){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_body_kb.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_KB_ADD){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_kb_add.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_KB_TABLE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_kb_table.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_PUBLISH){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_publish.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_GRAPH){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_body_graph.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_WF_PAGES){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_wf_body_pages.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_FORM_CREATE){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_form_create.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == WIZ_FORM_UI){
    if (isset( $_SESSION ['user_id']) && isset( $_SESSION ['user_name'])){
        include("views/sms/wiz/wiz_form_ui.php");
    }
    else {
        include("login.php");
    }
}
else if  ($login->getView() == LOGOUT_VIEW){
        $login->doLogout();
        include("login.php");
}
else {
    // the user is not logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are not logged in" view.
    include("index.html");
}

?>
