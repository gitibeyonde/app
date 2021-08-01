<?php

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once(__ROOT__.'/translations/en.php');
/**
 * VIEWS
 */

define("MAIN_VIEW", "main_view");
define("DEVICE_CONFIG", "config");
define("DEVICE_VIEW", "device_view");
define("DEVICE_SETTING", "device_setting");
define("DEVICE_SHARE", "device_share");
define("DEVICE_GRAPH", "device_graph");
define("DEVICE_PLACEMENT", "device_placement");
define("DEVICE_ALERTS", "device_alerts");
define("TEMP_VIEW", "temp_view");define("MOTION_VIEW", "motion_view");
define("IMAGE_PARAMS_VIEW", "image_params_view");
define("HISTORY_VIEW", "history_view");
define("HISTORY_TAG", "history_tag");
define("PLAY_HISTORY", "play_history");
define("FOR_IMAGE", "for_image");
define("FOR_IMAGE_HEADER", "for_image_header");
define("TEMP_DASH", "temp_dash");
define("LIVE_DASH", "live_dash");
define("VIDEO_DASH", "video_dash");
define("MOTION_DASH", "motion_dash");
define("USAGE_DASH", "usage_dash");
define("SETTINGS_DASH", "settings_dash");
define("USAGE_DETAILS", "usage_details");
define("ALERT_DASH", "alert_dash");
define("ALERT_CONFIG", "alert_config");
define("ALERT_DEVICE", "alert_device");
define("ANALYTICS", "analytics");
define("TAGS", "tags");
define("DEVICE_TAGS", "device_tags");
define("EVENTS_VIEW", "events_view");
define("LIVE_VIEW", "live_view");
define("VIDEO_VIEW", "video_view");
define("LIVE_SNAP", "live_snap");
define("RECORD_PLAY", "record_play");
define("RECORD_MP4PLAY", "record_mp4play");
define("RECORD_MANAGE", "record_manage");
define("REFRESH_VIEW", "refresh_view");
define("DEVICE_CONFIG_VIEW", "device_config_view");
define("SIP_VIEW", "sip_view");
define("PROFILE_VIEW", "profile_view");
define("LOGOUT_VIEW", "logout_view");

//SIM

define("SMS_AUDIENCE", "sms_audience");
define("SMS_PAY", "sms_pay");
define("SMS_DETAIL", "sms_detail");
define("SMS_COMPOSE", "sms_compose");
define("ADD_NUMBER", "add_number");
define("ASSIGN_NUMBER", "assign_number");
define("ADD_VIRTUAL", "add_virtual");
define("URL_MINIFY", "url_minify");

define("USER_USAGE", "user_usage");
define("USER_BILLING", "user_billing");
define("USER_ACCOUNT", "user_account");
define("USER_HOST_KEY", "user_host_key");
define("USER_CHARGE", "user_charge");


define("SMS_MESSAGE", "sms_message");
define("MESSAGE_TEMPLATE", "message_template");
define("MESSAGE_TEMPLATE_SMS", "message_template_sms");
define("MESSAGE_TEMPLATE_EMAIL", "message_template_email");
define("SMS_TRIGGER", "sms_trigger");
define("SMS_SURVEY", "sms_survey");
define("DELIVER_MICROAPP", "deliver_microapp");
define("PAYMENT_SETUP", "payment_setup");

define("WORKFLOW_LISTING", "workflow_listing");
define("WORKFLOW_NODE", "workflow_node");
define("WORKFLOW_ACTION", "workflow_action");
define("WORKFLOW_HELP", "workflow_help");
define("WORKFLOW_PLAYER", "workflow_player");
define("WORKFLOW_CONNECT", "workflow_connect");
define("WORKFLOW_MESSAGES", "workflow_messages");
define("AUDIENCE_DB_TABLE", "audience_db_table");
define("USER_DATA", "user_data");
define("USER_REPORT", "user_report");
define("USER_DATA_TABLE", "user_data_table");
define("MOBILE_USER_DATA", "mobile_user_data");
define("MOBILE_USER_SCANNER", "mobile_user_scanner");
define("MOBILE_KB", "mobile_kb");


define("FORUM_LISTING", "forum_listing");
define("FORUM_CREATE_TOPIC", "forum_create_topic");
define("FORUM_TOPIC", "forum_topic");

define("WIZ_FORM_LISTING", "wiz_form_listing");
define("WIZ_FORM_CREATE", "wiz_form_create");
define("WIZ_FORM_UI", "wiz_form_ui");

define("WIZ_WF_CATEGORY", "wiz_wf_category");
define("WIZ_WF_DESC", "wiz_wf_desc");
define("WIZ_WF_DEMO", "wiz_wf_demo");
define("WIZ_WF_LOGO", "wiz_wf_logo");
define("WIZ_WF_IMAGES", "wiz_wf_images");
define("WIZ_WF_KB", "wiz_wf_kb");
define("WIZ_WF_KB_ADD", "wiz_wf_kb_add");
define("WIZ_WF_KB_TABLE", "wiz_wf_kb_table");
define("WIZ_WF_PUBLISH", "wiz_wf_publish");
define("WIZ_WF_PAGES", "wiz_wf_pages");
define("WIZ_WF_GRAPH", "wiz_wf_graph");



define("ADMIN_INTENT", "admin_intent");
define("ADMIN_WORKFLOW", "admin_workflow");
define("ADMIN_WF_NODES", "admin_wf_nodes");
define("ADMIN_WF_EDITOR", "admin_wf_editor");
define("ADMIN_WF_DATA", "admin_wf_data");
define("ADMIN_CHATDB", "admin_chatdb");

define("ADMIN_MAIN_VIEW", "admin_main_view");
define("ADMIN_USAGE", "admin_usage");

define("RZRID","rzp_live_y7DZ0tpYujza3v");
define("RZRSEC","R7j1U1hlWJZDJNPCmyaOBb1N");
/**
 * Configuration for: Database Connection
 * This is the place where your database login constants are saved
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 *
 * DB_HOST: database host, usually it's "127.0.0.1" or "localhost", some servers also need port info
 * DB_NAME: name of the database. please note: database and database table are not the same thing
 * DB_USER: user for your database. the user needs to have rights for SELECT, UPDATE, DELETE and INSERT.
 *          by the way, it's bad style to use "root", but for development it will work.
 * DB_PASS: the password of the above user
 */
define("DB_HOST", "mysql.ibeyonde.com");
define("DB_NAME", "ibe");
define("DB_USER", "admin");
define("DB_PASS", "1b6y0nd6");

/**
 * Configuration for: Cookies
 * Please note: The COOKIE_DOMAIN needs the domain where your app is,
 * in a format like this: .mydomain.com
 * Note the . in front of the domain. No www, no http, no slash here!
 * For local development .127.0.0.1 or .localhost is fine, but when deploying you should
 * change this to your real domain, like '.mydomain.com' ! The leading dot makes the cookie available for
 * sub-domains too.
 * @see http://stackoverflow.com/q/9618217/1114320
 * @see http://www.php.net/manual/en/function.setcookie.php
 *
 * COOKIE_RUNTIME: How long should a cookie be valid ? 1209600 seconds = 2 weeks
 * COOKIE_DOMAIN: The domain where the cookie is valid for, like '.mydomain.com'
 * COOKIE_SECRET_KEY: Put a random value here to make your app more secure. When changed, all cookies are reset.
 */
define("COOKIE_RUNTIME", 1209600);
define("COOKIE_DOMAIN", $_SERVER['SERVER_NAME']);
define("COOKIE_SECRET_KEY", "1gp@TMPS{+$78sfpMJFe-92s");

/**
 * Configuration for: S3
 */
define("S3_VERSION", "latest");
define("S3_KEY", "AKIAYLP7ZL2EH5UYVH6M");
define("S3_SECRET", "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");
define("S3_REGION", "us-west-2");



/**
 * Configuration for: Email server credentials
 */

define("SES_VERSION", "latest");
define("SES_KEY", "AKIAJRYQJ3FXMKJYU4CQ");
define("SES_SECRET", "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");
define("SES_REGION", "us-west-2");

/**
 * Configuration for: password reset email data
 * Set the absolute URL to password_reset.php, necessary for email password reset links
 */
define("EMAIL_PASSWORDRESET_URL", "https://".$_SERVER['SERVER_NAME']."/password_reset.php");
define("EMAIL_PASSWORDRESET_FROM", "no_reply@".$_SERVER['SERVER_NAME']);
define("EMAIL_PASSWORDRESET_FROM_NAME", "Smart Devices On Cloud");
define("EMAIL_PASSWORDRESET_SUBJECT", "Password reset on ".$_SERVER['SERVER_NAME']);
define("EMAIL_PASSWORDRESET_CONTENT", "Please click on this link to reset your password:");

/**
 * Configuration for: verification email data
 * Set the absolute URL to register.php, necessary for email verification links
 */
define("EMAIL_VERIFICATION_URL", "https://".$_SERVER['SERVER_NAME']."/register.php");
define("EMAIL_VERIFICATION_FROM", "no_reply@".$_SERVER['SERVER_NAME']);
define("EMAIL_VERIFICATION_FROM_NAME", "Smart Devices On Cloud");
define("EMAIL_VERIFICATION_SUBJECT", "Account activation on ".$_SERVER['SERVER_NAME']);
define("EMAIL_VERIFICATION_CONTENT", "Please click on following link to activate your account: ");

/**
 * Configuration for: Hashing strength
 * This is the place where you define the strength of your password hashing/salting
 *
 * To make password encryption very safe and future-proof, the PHP 5.5 hashing/salting functions
 * come with a clever so called COST FACTOR. This number defines the base-2 logarithm of the rounds of hashing,
 * something like 2^12 if your cost factor is 12. By the way, 2^12 would be 4096 rounds of hashing, doubling the
 * round with each increase of the cost factor and therefore doubling the CPU power it needs.
 * Currently, in 2013, the developers of this functions have chosen a cost factor of 10, which fits most standard
 * server setups. When time goes by and server power becomes much more powerful, it might be useful to increase
 * the cost factor, to make the password hashing one step more secure. Have a look here
 * (@see https://github.com/panique/php-login/wiki/Which-hashing-&-salting-algorithm-should-be-used-%3F)
 * in the BLOWFISH benchmark table to get an idea how this factor behaves. For most people this is irrelevant,
 * but after some years this might be very very useful to keep the encryption of your database up to date.
 *
 * Remember: Every time a user registers or tries to log in (!) this calculation will be done.
 * Don't change this if you don't know what you do.
 *
 * To get more information about the best cost factor please have a look here
 * @see http://stackoverflow.com/q/4443476/1114320
 *
 * This constant will be used in the login and the registration class.
 */
define("HASH_COST_FACTOR", "10");
