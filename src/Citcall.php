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
	function __construct($userid,$apikey){
		if  (in_array  ('curl', get_loaded_extensions())) {
			$this->userid = $userid;
			$this->apikey = $apikey;
		}else{
			die("CURL is not available on your web server");
		}
	}

	/**
	 * Synchronous miscall
	 *
	 * @param	array	$param
	 * @return	array
	 */
	public function sync_miscall(array $param){
		$method = "sync_miscall";
		$ret = $this->sendRequest($param,$method);
		return json_decode($ret,true);
	}

	/**
	 * Asynchronous miscall
	 *
	 * @param	array	$param
	 * @return	array
	 */
	public function async_miscall(array $param){
		$method = "async_miscall";
		$ret = $this->sendRequest($param,$method);
		return json_decode($ret,true);
	}

	/**
	 * SMS
	 *
	 * @param	array	$param
	 * @return	array
	 */
	public function sms(array $param){
		$method = "sms";
		$ret = $this->sendRequest($param,$method);
		return json_decode($ret,true);
	}

	/**
	 * Sending request to Citcall API
	 *
	 * @param	array	$param
	 * @param	string	$method
	 * @return	string
	 */
	protected function sendRequest(array $param,$method){
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
}