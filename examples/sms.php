<?php
//example of sending an sms using an userid / API key
require_once '../vendor/autoload.php';

//create citcall with userid and API key
$citcall = new Citcall\Citcall(USERID,APIKEY);

//send message using simple api params
$sms = $citcall->sms([
	'senderid' => 'citcall',
	'msisdn' => MSISDN,
	'text' => 'Test message from the Citcall PHP'
]);

//array access provides response data
print_r($sms);
