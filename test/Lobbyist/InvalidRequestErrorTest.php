<?php

class Lobbyist_InvalidRequestErrorTest extends Lobbyist_TestCase
{
  public function testInvalidObject()
  {
    authorizeFromEnv();
    try
    {
      Lobbyist_Contact::find('invalid');
    }
    catch (Lobbyist_InvalidRequestError $e)
    {
      $this->assertEqual(404, $e->getHttpStatus());
    }
  }

  public function testBadData()
  {
    authorizeFromEnv();
    try
    {
      Lobbyist_Contact::create();
    }
    catch (Lobbyist_InvalidRequestError $e)
    {
      $this->assertEqual(412, $e->getHttpStatus());
    }
  }
}
