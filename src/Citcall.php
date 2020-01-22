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
namespace Citcall;
/**
 * Citcall Request Class
 *
 * @version 	3
 * @author		Citcall Dev Team
 * @link		https://docs.citcall.com/
*/
class Citcall {

	const URL_CITCALL 			= "https://gateway.citcall.com/";
	const VERSION 				= "/v3";
	const METHOD_SYNC_MISCALL	= "/call";
	const METHOD_ASYNC_MISCALL	= "/asynccall";
	const METHOD_SMS 			= "/sms";
	const METHOD_SMSOTP			= "/smsotp";
	const METHOD_VERIFY_MOTP 	= "/verify";

	/**
	 * Userid
	 *
	 * @var	string
	 */
	private $userid = "";

	/**
	 * API key
	 *
	 * @var	string
	 */
	private $apikey = "";

	// --------------------------------------------------------------------

	/**
	 * Constructor - Sets Authorization
	 *
	 * The constructor require userid and apikey
	 *
	 * @param	string	$userid
	 * @param	string	$apikey
	 * @return	void
	 */
	function __construct($userid,$apikey) {
		if(in_array  ('curl', get_loaded_extensions())) {
			$this->userid = $userid;
			$this->apikey = $apikey;
		} else {
			die("CURL is not available on your web server");
		}
	}

	/**
	 * Synchronous miscall
	 *
	 * @param	array	$param
	 * @return	array
	 */
	public function sync_miscall(array $param) {
		if(array_key_exists("msisdn",$param) && array_key_exists("gateway",$param)) {
			$msisdn = $param['msisdn'];
			$gateway = $param['gateway'];
			if($gateway > 5 || $gateway < 0) {
				$ret = array(
					"rc" => 06,
					"info" => "invalid gateway"
				);
				return $ret;
			} else {
				$continue = false;
				$msisdn = $this->cleanMsisdn($msisdn);
				$msisdn = preg_replace('/[^0-9]/', '',$msisdn);
				if (substr($msisdn,0,2)=='62') {
					if(strlen($msisdn) > 10 && strlen($msisdn) < 15) {
						$prefix = substr($msisdn,0,5);
						if(strlen($msisdn) > 13){
							if($this->isThree($prefix))
								$continue = true;
						} else {
							$continue = true;
						}
					}
				} else {
					if(strlen($msisdn) > 9 && strlen($msisdn) < 18)
						$continue = true;
				}

				if($continue) {
					$param_hit = array(
						"msisdn" => $msisdn,
						"gateway" => $gateway
					);
					$valid_verify = true;
					if(array_key_exists("valid_time",$param)) {
						$valid_time = filter_var($param['valid_time'], FILTER_VALIDATE_INT);
						if( is_int($valid_time) && $valid_time > 0 ) {
							if(array_key_exists("limit_try",$param)) {
								$limit_try = filter_var($param['limit_try'], FILTER_VALIDATE_INT);
								if( !is_int($valid_time) && $valid_time <= 0 ){
									$valid_verify = false;
								} else {
									$param_hit['valid_time'] = $valid_time;
									$param_hit['limit_try'] = $limit_try;
								}
							}
						} else {
							$valid_verify = false;
						}
					}
					if($valid_verify) {
						$method = "sync_miscall";
						$ret = $this->sendRequest($param_hit,$method);
					} else {
						$ret = array(
							"rc" => "06",
							"info" => "invalid verify data"
						);
						return $ret;
					}
				} else {
					$ret = array(
						"rc" => "06",
						"info" => "invalid mobile number"
					);
					return $ret;
				}
			}
		} else {
			$ret = array(
				"rc" => "88",
				"info" => "missing parameter"
			);
			return $ret;
		}
		return json_decode($ret,true);
	}

	/**
	 * Asynchronous miscall
	 *
	 * @param	array	$param
	 * @return	array
	 */
	public function async_miscall(array $param) {
		if(array_key_exists("msisdn",$param) && array_key_exists("gateway",$param)) {
			$msisdn = $param['msisdn'];
			$gateway = $param['gateway'];
			if($gateway > 5 || $gateway < 0) {
				$ret = array(
					"rc" => 06,
					"info" => "invalid gateway"
				);
				return $ret;
			} else {
				$continue = false;
				$msisdn = $this->cleanMsisdn($msisdn);
				$msisdn = preg_replace('/[^0-9]/', '',$msisdn);
				if (substr($msisdn,0,2)=='62') {
					if(strlen($msisdn) > 10 && strlen($msisdn) < 15) {
						$prefix = substr($msisdn,0,5);
						if(strlen($msisdn) > 13){
							if($this->isThree($prefix))
								$continue = true;
						} else {
							$continue = true;
						}
					}
				} else {
					if(strlen($msisdn) > 9 && strlen($msisdn) < 18)
						$continue = true;
				}

				if($continue) {
					$param_hit = array(
						"msisdn" => $msisdn,
						"gateway" => $gateway
					);
					$valid_verify = true;
					if(array_key_exists("valid_time",$param)) {
						$valid_time = filter_var($param['valid_time'], FILTER_VALIDATE_INT);
						if( is_int($valid_time) && $valid_time > 0 ) {
							if(array_key_exists("limit_try",$param)) {
								$limit_try = filter_var($param['limit_try'], FILTER_VALIDATE_INT);
								if( !is_int($valid_time) && $valid_time <= 0 ){
									$valid_verify = false;
								} else {
									$param_hit['valid_time'] = $valid_time;
									$param_hit['limit_try'] = $limit_try;
								}
							}
						} else {
							$valid_verify = false;
						}
					}
					if($valid_verify) {
						$method = "async_miscall";
						$ret = $this->sendRequest($param_hit,$method);
					} else {
						$ret = array(
							"rc" => "06",
							"info" => "invalid verify data"
						);
						return $ret;
					}
				} else {
					$ret = array(
						"rc" => "06",
						"info" => "invalid mobile number"
					);
					return $ret;
				}
			}
		} else {
			$ret = array(
				"rc" => "88",
				"info" => "missing parameter"
			);
			return $ret;
		}
		return json_decode($ret,true);
	}

	/**
	 * SMS
	 *
	 * @param	array	$param
	 * @return	array
	 */
	public function sms(array $param) {
		if(
			array_key_exists("msisdn",$param)
			&& array_key_exists("senderid",$param)
			&& array_key_exists("text",$param)
		) {
			$msisdn = $param['msisdn'];
			$senderid = $param['senderid'];
			$text = $param['text'];

			$msisdn = $this->formattingMsisdn($msisdn);

			if(strtolower(trim($senderid)) == "citcall")
				$senderid = strtoupper($senderid);

			$param_hit = array(
				"msisdn" => $msisdn,
				"senderid" => $senderid,
				"text" => $text 
			);

			$method = "sms";
			$ret = $this->sendRequest($param_hit,$method);
		} else {
			$ret = array(
				"rc" => "88",
				"info" => "missing parameter"
			);
			return $ret;
		}
		return json_decode($ret,true);
	}

	private function formattingMsisdn($origin_msisdn)
    {
        $origin_msisdn = explode(",", $origin_msisdn);

        $array_baru = array();

        foreach($origin_msisdn as $v) {
            $msisdn = $this->cleanMsisdn($v);
            $msisdn = preg_replace('/[^0-9]/', '',$msisdn);
            if (substr($msisdn,0,2)=='62') {
                if(strlen($msisdn) > 10 && strlen($msisdn) < 15) {
                    $prefix = substr($msisdn,0,5);
                    if(strlen($msisdn) > 13) {
                        if($this->isThree($prefix)) {
                            array_push($array_baru,$msisdn);
                        } else {
                            $ret = array(
                                "rc" => "06",
                                "info" => "invalid msisdn or msisdn has invalid format!"
                            );
                            return $ret;
                        }
                    } else {
                        array_push($array_baru,$msisdn);
                    }
                } else {
                    $ret = array(
                        "rc" => "06",
                        "info" => "invalid msisdn or msisdn has invalid format!"
                    );
                    return $ret;
                }
            } else {
                if(strlen($msisdn) > 9 && strlen($msisdn) < 18) {
                    array_push($array_baru,$msisdn);
                } else {
                    $ret = array(
                        "rc" => "06",
                        "info" => "invalid msisdn or msisdn has invalid format!"
                    );
                    return $ret;
                }
            }
        }

        return implode(",", $array_baru);
    }


    public function smsotp(array $param) {
        if(
            array_key_exists("msisdn", $param)
            && array_key_exists("senderid", $param)
            && array_key_exists("text", $param)
            && array_key_exists("token", $param)
        ) {
            $msisdn = $param['msisdn'];
            $senderid = $param['senderid'];
            $text = $param['text'];
            $token = $param['token'];

            $msisdn = $this->formattingMsisdn($msisdn);

            if(strtolower(trim($senderid)) == "citcall")
                $senderid = strtoupper($senderid);

            $param_hit = array(
                "msisdn" => $msisdn,
                "senderid" => $senderid,
                "text" => $text,
                "token" => $token
            );

            $method = "smsotp";
            $ret = $this->sendRequest($param_hit,$method);
        } else {
            $ret = array(
                "rc" => "88",
                "info" => "missing parameter"
            );
            return $ret;
        }
        return json_decode($ret,true);
    }


    /**
	 * Verify
	 *
	 * @param	array	$param
	 * @return	array
	 */
	public function verify_motp(array $param) {
		if(array_key_exists("msisdn",$param) && array_key_exists("trxid",$param) && array_key_exists("token",$param)) {
			if(is_numeric($param['token'])) {
				if(strlen($param['token']) > 3) {
					$msisdn = $param['msisdn'];
					$trxid = $param['trxid'];
					$token = $param['token'];
					$continue = false;
					$msisdn = $this->cleanMsisdn($msisdn);
					$msisdn = preg_replace('/[^0-9]/', '',$msisdn);
					if (substr($msisdn,0,2)=='62') {
						if(strlen($msisdn) > 10 && strlen($msisdn) < 15) {
							$prefix = substr($msisdn,0,5);
							if(strlen($msisdn) > 13) {
								if($this->isThree($prefix))
									$continue = true;
							} else {
								$continue = true;
							}
						}
					} else {
						if(strlen($msisdn) > 9 && strlen($msisdn) < 18)
							$continue = true;
					}
					if($continue) {
						$param_hit = array(
							"msisdn" => $msisdn,
							"trxid" => $trxid,
							"token" => $token
						);
						$method = "verify_motp";
						$ret = $this->sendRequest($param_hit,$method);
					} else {
						$ret = array(
							"rc" => "06",
							"info" => "invalid mobile number"
						);
						return $ret;
					}
				} else {
					$ret = array(
						"rc" => "06",
						"info" => "invalid token, token length minimum 4 digits"
					);
					return $ret;
				}
			} else {
				$ret = array(
					"rc" => "06",
					"info" => "nvalid token, token length minimum 4 digits"
				);
				return $ret;
			}
		} else {
			$ret = array(
				"rc" => "88",
				"info" => "missing parameter"
			);
			return $ret;
		}
		return json_decode($ret,true);
	}

	/**
	 * Sending request to Citcall API
	 *
	 * @param	array	$param
	 * @param	string	$method
	 * @return	string
	 */
	protected function sendRequest(array $param,$method) {
		$userid = $this->userid;
		$apikey = $this->apikey;

		$auth = base64_encode($userid . ':' . $apikey);

		switch ($method) {
			case 'sync_miscall':
				$action = self::METHOD_SYNC_MISCALL;
				break;
			case 'async_miscall':
				$action = self::METHOD_ASYNC_MISCALL;
				break;
			case 'sms':
				$action = self::METHOD_SMS;
				break;
			case 'smsotp':
				$action = self::METHOD_SMSOTP;
				break;
			case 'verify_motp':
				$action = self::METHOD_VERIFY_MOTP;
				break;
			default:
				# code...
				break;
		}

		$url = self::URL_CITCALL . self::VERSION . $action ;

		$content = json_encode($param);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: '. $auth,
			'Content-Length: '.strlen($content))
		);

		$response  = curl_exec($ch);

		curl_close($ch);

		return $response;
	}

	/**
	 * Clean MSISDN
	 *
	 * @param	string	$msisdn
	 * @return	string 	$msisdn
	 */
	protected function cleanMsisdn($msisdn) {
		if(substr($msisdn,0,1)<>'+')
			$msisdn="+".$msisdn;
		if(substr($msisdn,0,2)=='+0')
			$msisdn = "+62".substr($msisdn, 2);
		if(substr($msisdn,0,1)=='0')
			$msisdn = "+62".substr($msisdn, 1);
		return $msisdn;
	}

	/**
	 * cek prefix is three
	 *
	 * @param	string	$prefix
	 * @return	boolean
	 */
	protected function isThree($prefix) {
		switch ($prefix) {
			case '62896':
				return true;
				break;
			case '62897':
				return true;
				break;
			case '62898':
				return true;
				break;
			case '62899':
				return true;
				break;
			case '62895':
				return true;
				break;
			default:
				return false;
				break;
		}
	}
}
