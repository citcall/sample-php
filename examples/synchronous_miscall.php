<?php
//example of make a Synchronous miscall using an userid / API key
require_once '../vendor/autoload.php';

//create citcall with userid and API key
$citcall = new Citcall\Citcall(USERID,APIKEY);

//make Synchronous miscall using simple api params
$miscall = $citcall->sync_miscall([
	"msisdn" => MSISDN,
	"gateway" => GATEWAY
]);

//array access provides response data
print_r($miscall);