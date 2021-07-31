<?php
define ( '__ROOT__',  dirname(dirname(dirname(dirname ( __FILE__ )))));
//error_log("Root=".__ROOT__);
require_once (__ROOT__ . '/classes/wf/actions/Action.php');
require_once (__ROOT__ . '/classes/wf/actions/ActionExtract.php');
require_once (__ROOT__ . '/classes/wf/actions/ActionChoice.php');
require_once (__ROOT__ . '/classes/wf/actions/ActionIntent.php');
require_once (__ROOT__ . '/classes/wf/actions/ActionSearch.php');
require_once (__ROOT__ . '/classes/wf/SmsWfUtils.php');
require_once (__ROOT__ . '/classes/core/Log.php');

$log = new Log("info");
$GLOBALS['log']=$log;
$GLOBALS['user_id']=95;

$str ='Hi <b>{{mydb/user_data/value/name/}}, </b><br/><br/>Thank you ! <br/><br/>\r\nPay a token Rs. 1</br>\r\n<p id="total">1</p>\r\n<iframe class="responsive-iframe" id="pay"></iframe>';

$A = new Action(95, "fcca525e27b", "919701199011");

$A->parse_pattern_string($str);








?>