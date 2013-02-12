<?php

class Lobbyist_ApiRequestor
{
  public $apiKey;

  public function __construct()
  {
    $this->_apiKey = Lobbyist::$apiKey;
  }

  public static function apiUrl($url='')
  {
    $apiBase = Lobbyist::$apiBase;
    return "$apiBase$url.json";
  }

  public static function utf8($value)
  {
    if (is_string($value))
      return utf8_encode($value);
    else
      return $value;
  }

  private static function _encodeObjects($d)
  {
    if ($d instanceof Lobbyist_ApiResource)
    {
      return $d->contact_id;
    }
    else if ($d === true)
    {
      return 'true';
    }
    else if ($d === false)
    {
      return 'false';
    }
    else if (is_array($d))
    {
      $res = array();
      foreach ($d as $k => $v)
	      $res[$k] = self::_encodeObjects($v);
      return $res;
    }
    else
    {
      return $d;
    }
  }

  public static function encode($d)
  {
    return http_build_query($d, null, '&');
  }

  public function request($meth, $url, $id = null, $params = null)
  {
    if(!$params)
      $params = array();

    if($id)
      $params['id'] = $id;
    
    $params['nonce'] = gmdate("Y-m-d H:i:s e");
    list($rbody, $rcode, $myApiKey) = $this->_requestRaw($meth, $url, $params);
    $resp = $this->_interpretResponse($rbody, $rcode);
    return array($resp, $myApiKey);
  }

  public function handleApiError($rbody, $rcode, $resp)
  {
    if (!is_array($resp) || !isset($resp['errors']))
      throw new Lobbyist_ApiError("Invalid response object from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody, $resp);
    $error = $resp['errors'];

    switch ($rcode)
    {
    case 400:
    case 404:
    case 412:
      throw new Lobbyist_InvalidRequestError(isset($error[0]) ? $error[0] : null, $rcode, $rbody, $resp);
    case 401:
      throw new Lobbyist_AuthenticationError(isset($error[0]) ? $error[0] : null, $rcode, $rbody, $resp);

    default:
      throw new Lobbyist_ApiError(isset($error[0]) ? $error[0] : null, $rcode, $rbody, $resp);
    }
  }

  private function _requestRaw($method, $url, $params)
  {
    ## TODO: Check validity of help string URLs and email addresses.
    $apiKey = Lobbyist::$apiKey;
    if (!$apiKey)
      throw new Lobbyist_AuthenticationError('No API key provided. (HINT: set your API key using "Lobbyist::setApiKey(<API-KEY>)".  You can find your API keys from the Customer Lobby web interface. See http://www.customerlobby.com/api for details, or email support@customerlobby.com if you have any questions.');
    $apiSecret = Lobbyist::$apiSecret;
    if (!$apiSecret)
      throw new Lobbyist_AuthenticationError('No API secret token provided. (HINT: set your API key using "Lobbyist::setApiSecret(<API-SECRET>)".  You can find your API keys from the Customer Lobby web interface. See http://www.customerlobby.com/api for details, or email support@customerlobby.com if you have any questions.');

    $absUrl = $this->apiUrl($url);
    $params = self::_encodeObjects($params);
    $signature = self::_generateSignature($params, $method);
    unset($params['id']);
    
    $headers = array('Authorization: Token token="' . $apiKey . '", signature="' . $signature . '"');
    if (Lobbyist::$apiVersion)
      $headers[] = 'Lobbyist-Version: ' . Lobbyist::$apiVersion;
    list($rbody, $rcode) = $this->_curlRequest($method, $absUrl, $headers, $params);
    return array($rbody, $rcode, $apiKey);
  }

  private function _interpretResponse($rbody, $rcode)
  {
    try
    {
      $resp = json_decode($rbody, true);
    }
    catch (Exception $e)
    {
      throw new Lobbyist_ApiError("Invalid response body from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody);
    }

    if ($rcode < 200 || $rcode >= 300)
    {
      $this->handleApiError($rbody, $rcode, $resp);
    }
    return $resp;
  }

  private function _generateSignature($params, $method)
  {
    $params['method'] = strtolower($method);
    ksort($params);
    
    $message = self::_generateMessage($params);
    $message = urlencode($message);
    $signature = hash_hmac('sha256', $message, Lobbyist::$apiSecret);

    // Remove parameters that will be generated automaticaly by Rails.
    unset($params['method']);
    
    return $signature;
  }
  
  private function _generateMessage($params)
  {
    $message = "";
    foreach($params as $key => $value)
    {
      if($message != "")
        $message .= "&";
      
      if(is_array($value))
        $message .= $key . "=" . self::_stringifyArray($value);
      else
        $message .= $key . "=" . $value; 
    }
    return $message;
  }
  
  private function _stringifyArray($array)
  {
    $message = "";
    foreach($array as $key => $value)
    {
      if($message == "")
        $message .= "{";
      else
        $message .= ", ";
      
      $message .= '"' . $key . '"=>"' . $value . '"';
    }
    $message .= "}";
    
    return $message;
  }
  
  private function _curlRequest($meth, $absUrl, $headers, $params)
  {
    $curl = curl_init();
    $meth = strtolower($meth);
    $opts = array();
    if ($meth == 'get')
    {
      $opts[CURLOPT_HTTPGET] = 1;
      if (count($params) > 0)
      {
	      $encoded = self::encode($params);
	      $absUrl = "$absUrl?$encoded";
      }
    }
    else if ($meth == 'post')
    {
      $opts[CURLOPT_POST] = 1;
      $opts[CURLOPT_POSTFIELDS] = self::encode($params);
    }
    else if ($meth == 'put')
    {
      $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
      if (count($params) > 0)
      {
        $encoded = self::encode($params);
        $absUrl = "$absUrl?$encoded";
      }
    }
    else if ($meth == 'delete')
    {
      $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
      if (count($params) > 0)
      {
	      $encoded = self::encode($params);
	      $absUrl = "$absUrl?$encoded";
      }
    }
    else
    {
      throw new Lobbyist_ApiError("Unrecognized method $meth");
    }

    $absUrl = self::utf8($absUrl);
    $opts[CURLOPT_URL] = $absUrl;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_CONNECTTIMEOUT] = 30;
    $opts[CURLOPT_TIMEOUT] = 80;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_HTTPHEADER] = $headers;

    curl_setopt_array($curl, $opts);
    $rbody = curl_exec($curl);

    $errno = curl_errno($curl);
    if ($errno == CURLE_SSL_CACERT ||
	$errno == CURLE_SSL_PEER_CERTIFICATE ||
	$errno == 77 // CURLE_SSL_CACERT_BADFILE (constant not defined in PHP though)
	) {
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      $rbody = curl_exec($curl);
    }

    if ($rbody === false) {
      $errno = curl_errno($curl);
      $message = curl_error($curl);
      curl_close($curl);
      $this->_handleCurlError($errno, $message);
    }

    $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return array($rbody, $rcode);
  }

  private function _handleCurlError($errno, $message)
  {
    $apiBase = Lobbyist::$apiBase;
    switch ($errno) {
    case CURLE_COULDNT_CONNECT:
    case CURLE_COULDNT_RESOLVE_HOST:
    case CURLE_OPERATION_TIMEOUTED:
      $msg = "Could not connect to Customer Lobby ($apiBase).  Please check your internet connection and try again.";
      break;
    default:
      $msg = "Unexpected error communicating with Customer Lobby.  If this problem persists, let us know at support@customerlobby.com.";
    }

    $msg .= "\n\n(Network error [errno $errno]: $message)";
    throw new Lobbyist_ApiConnectionError($msg);
  }
}
