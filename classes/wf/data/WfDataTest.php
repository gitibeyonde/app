<?php
define ( '__ROOT__', dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) );
error_log ( "Root=" . __ROOT__ );
require_once (__ROOT__ . '/classes/wf/data/WfData.php');
require_once (__ROOT__ . '/classes/wf/data/WfDb.php');
require_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
require_once (__ROOT__ . '/classes/core/Log.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/wf/utils/WfEdit.php');

$log = new Log ("info");
WfEdit::getListOfExtracts("bdbc24deb2a", "junk");
//WfEdit::getBotKBStructure(95, "bdbc24deb2a");
//echo WfEdit::getExtractionString("email", "string", "email");

?>