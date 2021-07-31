<?php
define ( '__ROOT__',  dirname(dirname(dirname(dirname ( __FILE__ )))));
require_once (__ROOT__ . '/classes/wf/utils/WfUtils.php');
require_once (__ROOT__ . '/classes/wf/utils/WfEdit.php');
require_once (__ROOT__ . '/classes/wf/data/WfMasterDb.php');
require_once (__ROOT__ . '/classes/core/Log.php');

$log = new Log("info");
//echo date('yMd_H_i');
$WFDB = new WfMasterDb();


echo WfEdit::getWorkflowExtractionsString($WFDB->getNodes("5fb31270ea"));

?>
