<?php

class Lobbyist_Contact extends Lobbyist_ApiResource
{
  public $contact_id;
  public $company_id;
  public $first_name;
  public $last_name;
  public $phone_daytime;
  public $phone_alt;
  public $email;
  public $address1;
  public $address2;
  public $city;
  public $state;
  public $zip;
  public $country;
  public $last_service_date;
  public $notes;
  public $email_disturb_dtatus;
  public $unsubscribe_reason;
  public $unsubscribed_at;
  public $exclude_referral_marketing;
  public $exclude_retention_marketing;
  public $facebook_like;
  public $facebook_login_review;
  public $twitter_follow;
  public $date_added;
  public $auto_review_id;
  public $auto_invite_id;
  public $auto_customer_call_id;
  public $auto_review_draft_id;
  public $facebook_uid;
  public $facebook_access_token;
  public $facebook_session_key;
  
  public function __construct($id = null)
  {
    if ($id)
      $this->contact_id = $id;
  }

  public static function search($params)
  {
    $class = get_class();
    return self::_createFrom(self::_search($class, $params));
  }
  
  public static function find($id)
  {
    $class = get_class();
    return self::_find($class, $id);
  }

  public static function findAll($params = null)
  {
    $class = get_class();
    return self::_createFrom(self::_findAll($class, $params));
  }

  public static function create($params = null)
  {
    $class = get_class();
    
    if($params)
    {
      $contact = array();
      $contact['contact'] = $params;
      return self::_createFrom(self::_create($class, $contact));
    }
    else
      return null;
  }

  public function update($params = null)
  {
    $class = get_class();
    
    if($params)
    {
      $contact = array();
      $contact['contact'] = $params;
      return self::_refreshFrom(self::_update($class, $this->contact_id, $contact));
    }
    else
      return null;
  }

  public function delete()
  {
    $class = get_class();
    
    return self::_refreshFrom(self::_delete($class, $this->contact_id));
  }

  protected function _refreshFrom($response)
  {
    return self::_instantiate($response[0]['contact'], $this);
  }

  protected static function _createFrom($response)
  {
    $data = $response[0];
    
    if(array_key_exists('contact', $data))
    {
      // Create a single contact.
      return self::_instantiate($data['contact']);
    }
    else if(array_key_exists('contacts', $data))
    {
      // Create a list of contacts.
      $list = array();
      foreach($data['contacts'] as $contact)
      {
        array_push($list, self::_instantiate($contact));
      }
      return $list;
    }

    return null;
  }
  
  protected static function _instantiate($data, $instance = null)
  {
    if(isset($data))
    {
      if(!$instance)
        $instance = new Lobbyist_Contact();

      if(isset($data['contact_id']))
        $instance->contact_id = $data['contact_id'];

      if(isset($data['company_id']))
        $instance->company_id = $data['company_id'];

      if(isset($data['first_name']))
        $instance->first_name = $data['first_name'];

      if(isset($data['last_name']))
        $instance->last_name = $data['last_name'];

      if(isset($data['phone_daytime']))
        $instance->phone_daytime = $data['phone_daytime'];

      if(isset($data['phone_alt']))
        $instance->phone_alt = $data['phone_alt'];

      if(isset($data['email']))
        $instance->email = $data['email'];

      if(isset($data['address1']))
        $instance->address1 = $data['address1'];

      if(isset($data['address2']))
        $instance->address2 = $data['address2'];

      if(isset($data['city']))
        $instance->city = $data['city'];

      if(isset($data['state']))
        $instance->state = $data['state'];

      if(isset($data['zip']))
        $instance->zip = $data['zip'];

      if(isset($data['country']))
        $instance->country = $data['country'];

      if(isset($data['last_service_date']))
        $instance->last_service_date = $data['last_service_date'];

      if(isset($data['notes']))
        $instance->notes = $data['notes'];

      if(isset($data['email_disturb_status']))
        $instance->email_disturb_status = $data['email_disturb_status'];

      if(isset($data['unsubscribe_reason']))
        $instance->unsubscribe_reason = $data['unsubscribe_reason'];

      if(isset($data['unsubscribed_at']))
        $instance->unsubscribed_at = $data['unsubscribed_at'];

      if(isset($data['exclude_referral_marketing']))
        $instance->exclude_referral_marketing = $data['exclude_referral_marketing'];

      if(isset($data['exclude_retention_marketing']))
        $instance->exclude_retention_marketing = $data['exclude_retention_marketing'];

      if(isset($data['facebook_like']))
        $instance->facebook_like = $data['facebook_like'];

      if(isset($data['facebook_login_review']))
        $instance->facebook_login_review = $data['facebook_login_review'];

      if(isset($data['twitter_follow']))
        $instance->twitter_follow = $data['twitter_follow'];

      if(isset($data['date_added']))
        $instance->date_added = $data['date_added'];

      if(isset($data['auto_review_id']))
        $instance->auto_review_id = $data['auto_review_id'];

      if(isset($data['auto_invite_id']))
        $instance->auto_invite_id = $data['auto_invite_id'];

      if(isset($data['auto_customer_call_id']))
        $instance->auto_customer_call_id = $data['auto_customer_call_id'];

      if(isset($data['auto_review_draft_key']))
        $instance->auto_review_draft_key = $data['auto_review_draft_key'];

      if(isset($data['facebook_uid']))
        $instance->facebook_uid = $data['facebook_uid'];

      if(isset($data['facebook_access_token']))
        $instance->facebook_access_token = $data['facebook_access_token'];

      if(isset($data['facebook_session_key']))
        $instance->facebook_session_key = $data['facebook_session_key'];
    }
    return $instance;
  }
}
