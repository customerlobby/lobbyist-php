<?php

class Lobbyist_ContactTest extends Lobbyist_TestCase
{
  private $createdId;
  
  public function testFindAll()
  {
    authorizeFromEnv();
    
    $contacts = Lobbyist_Contact::findAll();
    $this->assertNotNull($contacts);
    $this->assertIsA($contacts, 'Array');
    $this->assertIsA($contacts[0], 'Lobbyist_Contact');
    $this->assertNotNull($contacts[0]->id);
    $this->assertNotNull($contacts[0]->firstName);
  }
  
  public function testCreateAndFind()
  {
    authorizeFromEnv();
  
    $params = array();
    $params['first_name'] = "John";
    $params['last_name']  = "Doe";
    $params['phone_daytime'] = "123-987-4567";
    $params['email']         = "jdoe@nowhere.com";
    
    $contact = Lobbyist_Contact::create($params);
  
    $this->assertNotNull($contact->id);
    $this->createdId = $contact->id;
    $this->assertEqual($contact->email, 'jdoe@nowhere.com');
  
    $contact1 = Lobbyist_Contact::find($contact->id);
    $this->assertEqual($contact->email, $contact1->email);
  }
  
  public function testUpdate()
  {
    authorizeFromEnv();
    
    $contact = Lobbyist_Contact::find($this->createdId);
    
    $this->assertNotNull($contact->id);
  
    $params = array();
    $params['email'] = 'newemail@nowhere.com';
    $contact->update($params);
    
    $contact1 = Lobbyist_Contact::find($this->createdId);
    
    $this->assertNotNull($contact1->id);
    $this->assertEqual($contact1->email, 'newemail@nowhere.com');
  }
  
  public function testDeletion()
  {
    authorizeFromEnv();
  
    $contact = Lobbyist_Contact::find($this->createdId);
    
    $this->assertNotNull($contact->id);
  
    $contact->delete();
  
    try
    {
      $contact1 = Lobbyist_Contact::find($this->createdId);
    }
    catch (Lobbyist_InvalidRequestError $e)
    {
      $this->assertEqual(404, $e->getHttpStatus());
    }
  }
}