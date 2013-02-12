<?php

class Lobbyist_ApiRequestorTest extends Lobbyist_TestCase
{
  public function testEncode()
  {
    $a = array('my' => 'value', 'that' => array('your' => 'example'), 'bar' => 1, 'baz' => null);
    $enc = Lobbyist_APIRequestor::encode($a);
    $this->assertEqual($enc, 'my=value&that%5Byour%5D=example&bar=1');

    $a = array('that' => array('your' => 'example', 'foo' => null));
    $enc = Lobbyist_APIRequestor::encode($a);
    $this->assertEqual($enc, 'that%5Byour%5D=example');
  }

  public function testEncodeObjects()
  {
    // We have to do some work here because this is normally
    // private. This is just for testing! Also it only works on PHP >=
    // 5.3
    if (version_compare(PHP_VERSION, '5.3.2', '>='))
    {
      $reflector = new ReflectionClass('Lobbyist_APIRequestor');
      $method = $reflector->getMethod('_encodeObjects');
      $method->setAccessible(true);

      $a = array('contact' => new Lobbyist_Contact('abcd'));
      $enc = $method->invoke(null, $a);
      $this->assertEqual($enc, array('contact' => 'abcd'));
    }
  }
  
  public function testApiUrl()
  {
    $url = Lobbyist_APIRequestor::apiUrl();
    $this->assertEqual(Lobbyist::$apiBase . ".json", $url);
    
    $url = Lobbyist_APIRequestor::apiUrl('/contacts');
    $this->assertEqual(Lobbyist::$apiBase . "/contacts.json", $url);
  }
}
