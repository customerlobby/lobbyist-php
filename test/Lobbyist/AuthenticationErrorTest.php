<?php

class Lobbyist_AuthenticationErrorTest extends Lobbyist_TestCase
{
  public function testInvalidCredentials()
  {
    Lobbyist::setApiKey('invalid');
    Lobbyist::setApiSecret('invalid');
    try
    {
      Lobbyist_Contact::create();
    }
    catch (Lobbyist_AuthenticationError $e)
    {
      $this->assertEqual(401, $e->getHttpStatus());
    }
  }
}
