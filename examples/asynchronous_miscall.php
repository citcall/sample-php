<?php
//example of make a Asynchronous miscall using an userid / API key
require_once '../vendor/autoload.php';

//create citcall with userid and API key
$citcall = new Citcall\Citcall(USERID,APIKEY);

//make Asynchronous miscall using simple api params
$miscall = $citcall->async_miscall([
	"msisdn" => MSISDN,
	"gateway" => GATEWAY,
	//"valid_time" => TIME_VALID, //Time in second for valid OTP (If this parameter exist you will be able to do verify later).
	//"limit_try" => LIMIT_TRY //Maximum limit retry for verify with maximum allowed 20 (If this parameter doesn’t exist will be set automatically to 5).
]);

//array access provides response data
print_r($miscall);
