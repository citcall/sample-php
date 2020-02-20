<?php
/**
|-------------------------------------------------------------------
| CITCALL
|-------------------------------------------------------------------
| before you connect to the citcall API make sure that:
| You have read the citcall API documentation
|
|
*/
namespace Citcall;

/**
 * Citcall Request Class
 *
 * @version     3.1
 * @author      Citcall Dev Team
 * @link        https://docs.citcall.com/
*/
class Citcall {

    const URL_CITCALL           = "https://gateway.citcall.com/";
    const VERSION               = "/v3";
    const METHOD_SYNC_MISCALL   = "/call";
    const METHOD_ASYNC_MISCALL  = "/asynccall";
    const METHOD_SMS            = "/sms";
    const METHOD_SMSOTP         = "/smsotp";
    const METHOD_VERIFY         = "/verify";

    /**
     * API key
     *
     * @var string
     */
    private $apikey = "";


    /**
     * Paramerter
     *
     * @var array
     */
    private $parameters = array();


    private $max_retry_squence = 4;
    // --------------------------------------------------------------------

    /**
     * Constructor - Sets Authorization
     *
     * The constructor require apikey
     *
     * @param   string  $apikey
     * @return  void
     */
    function __construct($apikey) {
        if(in_array  ('curl', get_loaded_extensions())) {
            $this->apikey = $apikey;
        } else {
            die("CURL is not available on your web server");
        }
    }

    /**
     * Synchronous miscall
     *
     * @param   array   $param
     * @return  array
     */
    public function sync_miscall(array $param) {
        if(!array_key_exists("msisdn", $param) OR !array_key_exists("gateway", $param)) {
            $ret = array(
                "rc" => 88,
                "info" => "missing parameter"
            );
            return $ret;
        }
        $msisdn = $param['msisdn'];
        if(array_key_exists("gateway",$param)) {
            $gateway = filter_var($param['gateway'], FILTER_VALIDATE_INT);
            if(!is_int($gateway) OR $gateway < 0 OR $gateway > $this->max_retry_squence) {
                $ret = array(
                    "rc" => 6,
                    "info" => "invalid gateway"
                );
                return $ret;
            }
        }
        $msisdn = $this->cleanMsisdn($msisdn);
        if(!$this->isNumberValid($msisdn)) {
            $ret = array(
                "rc" => 6,
                "info" => "invalid mobile number"
            );
            return $ret;
        }
        $this->parameters['msisdn'] = $msisdn;
        $this->parameters['gateway'] = $gateway;
        if(!$this->isValidity($param)) {
            $ret = array(
                "rc" => 6,
                "info" => "invalid verify data"
            );
            return $ret;
        }
        $ret = $this->sendRequest("sync_miscall");
        return json_decode($ret, true);
    }

    /**
     * Asynchronous miscall
     *
     * @param   array   $param
     * @return  array
     */
    public function async_miscall(array $param) {
        if(!array_key_exists("msisdn", $param)) {
            $ret = array(
                "rc" => 88,
                "info" => "missing parameter"
            );
            return $ret;
        }
        if(!array_key_exists("gateway", $param) AND !array_key_exists("retry", $param)) {
            $ret = array(
                "rc" => 88,
                "info" => "missing parameter"
            );
            return $ret;
        }
        $msisdn = $param['msisdn'];
        if(array_key_exists("gateway",$param)) {
            $retry = filter_var($param['gateway'], FILTER_VALIDATE_INT);
            if(!is_int($retry) OR $retry < 0 OR $retry > $this->max_retry_squence) {
                $ret = array(
                    "rc" => 6,
                    "info" => "invalid gateway"
                );
                return $ret;
            }
        }
        if(array_key_exists("retry",$param)) {
            $retry = filter_var($param['retry'], FILTER_VALIDATE_INT);
            if(!is_int($retry) OR $retry < 0 OR $retry > $this->max_retry_squence) {
                $ret = array(
                    "rc" => 6,
                    "info" => "invalid retry"
                );
                return $ret;
            }
        }
        if(array_key_exists("callback_url",$param)) {
            $callback_url = $param['callback_url'];
            if(!isValidUrl($callback_url)) {
                $ret = array(
                    "rc" => 95,
                    "info" => "invalid callback URL"
                );
                return $ret;
            }
            $this->parameters['callback_url'] = $callback_url;
        }
        $msisdn = $this->cleanMsisdn($msisdn);
        if(!$this->isNumberValid($msisdn)) {
            $ret = array(
                "rc" => 6,
                "info" => "invalid mobile number"
            );
            return $ret;
        }
        $this->parameters['msisdn'] = $msisdn;
        $this->parameters['retry'] = $retry;
        if(!$this->isValidity($param)) {
            $ret = array(
                "rc" => 6,
                "info" => "invalid verify data"
            );
            return $ret;
        }
        $ret = $this->sendRequest("async_miscall");
        return json_decode($ret, true);
    }

    /**
     * Asynchronous miscall
     *
     * @param   array   $param
     * @return  array
     */
    public function miscall(array $param) {
        //only forward to async_miscall
        return $this->async_miscall($param);
    }

    /**
     * SMS
     *
     * @param   array   $param
     * @return  array
     */
    public function sms(array $param) {
        if(
            !array_key_exists("msisdn",$param) OR !array_key_exists("senderid",$param) OR
            !array_key_exists("text",$param)
        ) {
            $ret = array(
                "rc" => 88,
                "info" => "missing parameter"
            );
            return $ret;
        }
        $text = $param['text'];
        if($this->isContainOtpWords($text)) {
            $ret = array(
                "rc" => 6,
                "info" => "text not allowed use this method, use smsotp instead"
            );
            return $ret;
        }
        $msisdn = $param['msisdn'];
        $msisdn = $this->cleanMsisdn($msisdn);
        if(!$this->isNumberValid($msisdn)) {
            $ret = array(
                "rc" => 6,
                "info" => "invalid mobile number"
            );
            return $ret;
        }
        if(array_key_exists("callback_url",$param)) {
            $callback_url = $param['callback_url'];
            if(!isValidUrl($callback_url)) {
                $ret = array(
                    "rc" => 95,
                    "info" => "invalid callback URL"
                );
                return $ret;
            }
            $this->parameters['callback_url'] = $callback_url;
        }
        $senderid = $param['senderid'];
        if(strtolower(trim($senderid)) == "citcall")
            $senderid = strtoupper($senderid);
        $this->parameters['msisdn'] = $msisdn;
        $this->parameters['senderid'] = $senderid;
        $this->parameters['text'] = $text;
        $method = "sms";
        $ret = $this->sendRequest($method);
        
        return json_decode($ret,true);
    }

    /**
     * SMSOTP
     *
     * @param   array   $param
     * @return  array
     */
    public function smsotp(array $param) {
        if(
            !array_key_exists("msisdn",$param) OR !array_key_exists("senderid",$param) OR
            !array_key_exists("text",$param)
        ) {
            $ret = array(
                "rc" => 88,
                "info" => "missing parameter"
            );
            return $ret;
        }
        $msisdn = $param['msisdn'];
        $msisdn = $this->cleanMsisdn($msisdn);
        if(!$this->isNumberValid($msisdn)) {
            $ret = array(
                "rc" => 6,
                "info" => "invalid mobile number"
            );
            return $ret;
        }
        if(array_key_exists("callback_url",$param)) {
            $callback_url = $param['callback_url'];
            if(!isValidUrl($callback_url)) {
                $ret = array(
                    "rc" => 95,
                    "info" => "invalid callback URL"
                );
                return $ret;
            }
            $this->parameters['callback_url'] = $callback_url;
        }
        if(array_key_exists("token",$param)) {
            $token = $param['token'];
            if(!is_numeric($token)) {
                $ret = array(
                    "rc" => 6,
                    "info" => "invalid token, token must be numeric"
                );
                return $ret;
            }
            $this->parameters['token'] = $token;
        }
        if(!$this->isValidity($param)) {
            $ret = array(
                "rc" => 6,
                "info" => "invalid verify data"
            );
            return $ret;
        }
        $senderid = $param['senderid'];
        if(strtolower(trim($senderid)) == "citcall")
            $senderid = strtoupper($senderid);
        $text = $param['text'];
        $this->parameters['msisdn'] = $msisdn;
        $this->parameters['senderid'] = $senderid;
        $this->parameters['text'] = $text;
        $method = "smsotp";
        $ret = $this->sendRequest($method);
        
        return json_decode($ret,true);
    }

    /**
     * Verify
     *
     * @param   array   $param
     * @return  array
     */
    public function verify_motp(array $param) {
        if(
            !array_key_exists("msisdn",$param) OR !array_key_exists("trxid",$param) OR
            !array_key_exists("token",$param)) {
            $ret = array(
                "rc" => 88,
                "info" => "missing parameter"
            );
            return $ret;
        }
        $token = $param['token'];
        if(!is_numeric($token) OR strlen($token <= 3)) {
            $ret = array(
                "rc" => 6,
                "info" => "invalid token, token length minimum 4 digits"
            );
            return $ret;
        }
        $msisdn = $param['msisdn'];
        $msisdn = $this->cleanMsisdn($msisdn);
        if(!$this->isNumberValid($msisdn)) {
            $ret = array(
                "rc" => 6,
                "info" => "invalid mobile number"
            );
            return $ret;
        }
        $trxid = $param['trxid'];
        $this->parameters['msisdn'] = $msisdn;
        $this->parameters['trxid'] = $trxid;
        $this->parameters['token'] = $token;
        $method = "verify";
        $ret = $this->sendRequest($method);
        return json_decode($ret,true);
    }

    /**
     * Verify
     *
     * @param   array   $param
     * @return  array
     */
    public function verify(array $param) {
        //only forward to async_miscall
        return $this->verify_motp($param);
    }

    /**
     * Sending request to Citcall API
     *
     * @param   array   $param
     * @param   string  $method
     * @return  string
     */
    protected function sendRequest($method) {
        $auth = "Apikey " . $this->apikey;

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
            case 'verify':
                $action = self::METHOD_VERIFY;
                break;
            default:
                # code...
                break;
        }

        $url = self::URL_CITCALL . self::VERSION . $action ;

        $content = $this->safe_json_encode($this->parameters);
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

        $this->parameters = array();

        return $response;
    }

    /**
     * Clean MSISDN
     *
     * @param   string  $msisdn
     * @return  string  $msisdn
     */
    protected function cleanMsisdn($msisdn) {
        if(substr($msisdn,0,1) <> '+')
            $msisdn = "+" . $msisdn;
        if(substr($msisdn,0,2) == '+0')
            $msisdn = "+62" . substr($msisdn, 2);
        if(substr($msisdn,0,1) == '0')
            $msisdn = "+62" . substr($msisdn, 1);
        return preg_replace('/[^0-9]/', '',$msisdn);
    }

    /**
     * cek prefix is three
     *
     * @param   string  $prefix
     * @return  boolean
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

    /**
     * cek is text contain OTP words
     * @author mchairul
     *
     * @param   string  $string
     * @return  boolean
     */
    protected function isContainOtpWords($string) {
        $array = array("otp", "code", "kode", "password", "kata sandi", "one time password");
        $stripedString = str_ireplace($array, '', $string);
        return strlen($stripedString) !== strlen($string);
    }

    /**
     * safe encoding json
     * @see https://www.php.net/manual/en/function.json-encode.php
     * @author mchairul
     *
     * @param   string      $value - The value being encoded. Can be any type except a resource.
     *                               All string data must be UTF-8 encoded.  
     * @param   Integer     $options - Predefined Constants @see https://www.php.net/manual/en/json.constants.php
     * @param   Integer     $depth - Set the maximum depth. Must be greater than zero.
     * @return  Returns a JSON encoded string on success or excetion on failure.
     */
    public function safe_json_encode($value, $options = 0, $depth = 512, $utfErrorFlag = false) {
        $encoded = json_encode($value, $options, $depth);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_DEPTH:
                throw new Exception('JSON - Maximum stack depth exceeded');
            case JSON_ERROR_STATE_MISMATCH:
                throw new Exception('JSON - Underflow or the modes mismatch');
            case JSON_ERROR_CTRL_CHAR:
                throw new Exception('JSON - Unexpected control character found');
            case JSON_ERROR_SYNTAX:
                throw new Exception('JSON - Syntax error, malformed JSON');
            case JSON_ERROR_UTF8:
                $clean = $this->utf8ize($value);
                if ($utfErrorFlag) {
                    throw new Exception('JSON - UTF8 encoding error');
                }
                return safe_json_encode($clean, $options, $depth, true);
            default:
                throw new Exception('JSON - Unknown error');
        }
    }

    /**
     * Encodes an ISO-8859-1 string to UTF-8
     * @see https://www.php.net/manual/en/function.utf8-encode.php
     * @author mchairul
     *
     * @param   string      $mixed
     * @return  String      Returns the UTF-8 translation of data. 
     */
    protected function utf8ize($mixed) {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = utf8ize($value);
            }
        } else if (is_string($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }

    /**
     * cek is mobile number valid
     * @author mchairul
     *
     * @param   string  $msisdn
     * @return  mixed
     */
    protected function isNumberValid($msisdn) {
        //indonesia operator check
        if(substr($msisdn, 0, 2) == '62') {
            if(strlen($msisdn) > 10 && strlen($msisdn) <= 16) {
                $prefix = substr($msisdn,0 , 5);
                $continue = true;
                if(strlen($msisdn) > 13) {
                    return $this->isThree($prefix) ? true : false;
                }
                return true;
            }
            return false;
        } else {
            /**
             * international format 
             * @see https://www.itu.int/rec/T-REC-E.164/en
             */
            if(strlen($msisdn) > 9 && strlen($msisdn) < 18) {
                return preg_match('/^\++?[0-9]\d{6,14}$/', '+' . $msisdn) ? true : false;
            }
        }
    }

    /**
     * cek is validity data valid
     * @author mchairul
     *
     * @param   array  $param
     * @return  mixed
     */
    protected function isValidity($param) {
        if(array_key_exists("valid_time",$param)) {
            $valid_time = filter_var($param['valid_time'], FILTER_VALIDATE_INT);
            if(is_int($valid_time) && $valid_time > 0 ) {
                if(array_key_exists("limit_try",$param)) {
                    $limit_try = filter_var($param['limit_try'], FILTER_VALIDATE_INT);
                    if(is_int($limit_try) && $limit_try > 0 ) {
                        $this->parameters['valid_time'] = $valid_time;
                        $this->parameters['limit_try'] = $limit_try;
                        return true;
                    }
                }
            }
            return false;
        }
        return true;
    }

    /**
     * validate the URL
     * @author mchairul
     * @param string $url - with http://
     * @return boolean
     */
    protected function isValidUrl($url) {
        $path = parse_url($url, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $url = str_replace($path, implode('/', $encoded_path), $url);
        return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
    }
}
