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
//example of make a Verify OTP using an userid / API key.
require_once '../vendor/autoload.php';

//create citcall with userid and API key
$citcall = new Citcall\Citcall(USERID,APIKEY);

//make verify using simple api params
$miscall = $citcall->verify_motp([
	"msisdn" => MSISDN,
	"trxid" => TRXID, //Trxid from miscall request response
	"token" => TOKEN
]);

//array access provides response data
print_r($miscall);
