<?php
$_SERVER['SERVER_NAME']="ibeyonde.com";
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
$msg['Source'] = "no_reply@ibeyonde.com";
$msg['Destination']['ToAddresses'][] = "agneya2001@yahoo.com";

$msg['Message']['Subject']['Data'] = EMAIL_VERIFICATION_SUBJECT;
$msg['Message']['Subject']['Charset'] = "UTF-8";

$msg['Message']['Body']['Html']['Data'] = "Hello <br/> world";
$msg['Message']['Body']['Html']['Charset'] = "UTF-8";

try {
	$result = $client->sendEmail($msg);
	$msg_id = $result->get('MessageId');
	print_r($msg);
} catch (AwsException $e) {
    // output error message if fails
    echo $e->getMessage();
    echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
    echo "\n";
}
?>
