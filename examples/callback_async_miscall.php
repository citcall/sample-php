<?php
/**
|-------------------------------------------------------------------
| CITCALL
|-------------------------------------------------------------------
| In case you need to whitelist our IPs on you system, here is the 
| list of addresses we are using to delivery reports.
| 1. 104.199.196.122
*/
$data = file_get_contents('php://input');

$data_json = json_decode($data, true);

if (!$data_json) {
	//erro handling
} else {
	//you can acces callback data as below
	/*
	$rc = $data_json['rc'];
	$trxid = $data_json['trxid'];
	$msisdn = $data_json['msisdn'];
	$via = $data_json['via'];
	$token = $data_json['token'];
	$dial_code = $data_json['dial_code'];
	$dial_status = $data_json['dial_status'];
	$call_status = $data_json['call_status'];
	$result = $data_json['result'];
	*/
}
