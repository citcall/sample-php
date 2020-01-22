<?php
/**
|-------------------------------------------------------------------
| CITCALL
|-------------------------------------------------------------------
| before you connect to the citcall API make sure that:
| 1. You have read the citcall API documentation
| 2. your userid has been registered and your IP has been filtered in citcall system
|
*/
//example of sending an sms using an userid / API key
require_once '../vendor/autoload.php';

//create citcall with userid and API key
$citcall = new Citcall\Citcall(USERID,API_KEY);

$token = random_int(10000, 99999); // example generate token

//send message using simple api params
$sms = $citcall->smsotp([
	'senderid' => 'CITCALL',
	'msisdn' => MSISDN,
	'text' => 'Test message from the Citcall PHP XXXXX', // "XXXXX" will replaced by token param automatically
    'token' => $token,
]);

//array access provides response data
print_r($sms);
