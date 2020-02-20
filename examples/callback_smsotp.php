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
	$trxid = $data_json['trxid'];
	$result = $data_json['result'];
	$description = $data_json['description'];
	$reportedDate = $data_json['reportedDate'];
	*/
}
