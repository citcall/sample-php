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
//example of make a Synchronous miscall using an userid / API key
require_once '../vendor/autoload.php';

//create citcall with userid and API key
$citcall = new Citcall\Citcall(USERID,APIKEY);

//make Synchronous miscall using simple api params
$miscall = $citcall->sync_miscall([
	"msisdn" => MSISDN,
	"gateway" => GATEWAY,
	//"valid_time" => TIME_VALID, //Time in second for valid OTP (If this parameter exist you will be able to do verify later).
	//"limit_try" => LIMIT_TRY //Maximum limit retry for verify with maximum allowed 20 (If this parameter doesn’t exist will be set automatically to 5).
]);

//array access provides response data
print_r($miscall);
