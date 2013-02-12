<?php

/**
 * Base class for Lobbyist test cases, provides some utility methods for creating
 * objects.
 */
abstract class Lobbyist_TestCase extends UnitTestCase
{

  /**
   * Generate an HMAC for testing.
   */
  protected static function generateHmac($params, $method)
  {
//    $params['time'] = gmdate("Y-m-d H:i:s e");
    $params['method'] = strtolower($method);
    ksort($params);
    
    $message = "";
    foreach($params as $key => $value)
    {
      if($message != "")
        $message .= "&";
      $message .= $key . "=" . $value;
    }
    $message = urlencode($message);
    $hmac = hash_hmac('sha256', $message, Lobbyist::$apiSecret);
    unset($params['method']);
    
    return $hmac;
  }

  /**
   * Create a params array suitable for testing.
   */
  protected static function getParams()
  {
    $params = array();
    $params['time'] = gmdate("Y-m-d H:i:s e");
    
    return $params;
  }

  /**
   * Generate a random 8-character string. Useful for ensuring
   * multiple test suite runs don't conflict
   */
  protected static function randomString()
  {
    $chars = "abcdefghijklmnopqrstuvwxyz";
    $str = "";
    for ($i = 0; $i < 10; $i++) {
      $str .= $chars[rand(0, strlen($chars)-1)];
    }
    return $str;
  }
}
