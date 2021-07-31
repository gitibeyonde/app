<?php
$_SERVER['SERVER_NAME']="1do.in";
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/libraries/aws.phar');
require_once(__ROOT__.'/config/config.php');


use Aws\Ses\SesClient;


$client = SesClient::factory(array(
        'version' => SES_VERSION,
        'key'    => SES_KEY,
        'secret' => SES_SECRET,
        'region' => SES_REGION
));

$msg = array();
$msg['Source'] = "app@1do.in";
$msg['Destination']['ToAddresses'][] = "info@ibeyonde.com";

$msg['Message']['Subject']['Data'] = EMAIL_VERIFICATION_SUBJECT;
$msg['Message']['Subject']['Charset'] = "UTF-8";

$msg['Message']['Body']['Html']['Data'] = "Hello <br/> world";
$msg['Message']['Body']['Html']['Charset'] = "UTF-8";

$result = $client->sendEmail($msg);
$msg_id = $result->get('MessageId');

error_log("info@ibeyonde.com"." Msg id=".$msg_id. " sent from ".EMAIL_VERIFICATION_FROM);

print_r($msg);

?>
