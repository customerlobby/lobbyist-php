<?php

abstract class Lobbyist_ApiResource
{
  public function refresh()
  {
    $requestor = new Lobbyist_ApiRequestor();
    $url = $this->instanceUrl();

    $response = $requestor->request('get', $url, $this->id);
    $this->_refreshFrom($response);
    return $this;
   }

  public static function className($class)
  {
    // Useful for namespaces: Foo\Lobbyist_Contact
    if ($postfix = strrchr($class, '\\'))
      $class = substr($postfix, 1);
    if (substr($class, 0, strlen('Lobbyist')) == 'Lobbyist')
      $class = substr($class, strlen('Lobbyist'));
    $class = str_replace('_', '', $class);
    $name = urlencode($class);
    $name = strtolower($name);
    return $name;
  }

  public static function classUrl($class)
  {
    $base = self::className($class);
    return "/v1/${base}s";
  }

  public function instanceUrl()
  {
    $class = get_class($this);
    if (!$this->id)
    {
      throw new Lobbyist_InvalidRequestError("Could not determine which URL to request: $class instance has invalid Id.", null);
    }
    $id = Lobbyist_ApiRequestor::utf8($this->id);
    $base = self::classUrl($class);
    $extn = urlencode($id);
    return "$base/$extn";
  }

  abstract protected function _refreshFrom($response);
  
  protected static function _search($class, $params)
  {
    $requestor = new Lobbyist_ApiRequestor();
    $url = self::classUrl($class) . '/search';
    return $requestor->request('get', $url, null, $params);
  }
  
  protected static function _find($class, $id)
  {
    $instance = new $class($id);
    $instance->refresh();
    return $instance;
  }

  protected static function _findAll($class, $params = null)
  {
    $requestor = new Lobbyist_ApiRequestor();
    $url = self::classUrl($class);
    $response = $requestor->request('get', $url, null, $params);
    return $response;
  }

  protected static function _create($class, $params = null)
  {
    $requestor = new Lobbyist_ApiRequestor();
    $url = self::classUrl($class);
    $response = $requestor->request('post', $url, null, $params);
    return $response;
  }

  protected function _update($class, $id, $params = null)
  {
    $requestor = new Lobbyist_ApiRequestor();
    $url = self::instanceUrl($class);
    $response = $requestor->request('put', $url, $id, $params);
    return $response;
  }

  protected function _delete($class, $id, $params = null)
  {
    $requestor = new Lobbyist_ApiRequestor();
    $url = $this->instanceUrl();
    $response = $requestor->request('delete', $url, $id, $params);
    return $response;
  }
}
