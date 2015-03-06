<?php
/**
*
*/
class AuthenticationServer
{
  /*
|---------------------------------------------------------------------
|   These are authentication singletons that handle signing in
|   They are instances of third-party clients for oauth
|---------------------------------------------------------------------
*/
  static $twitter;
  static $facebook;
  static $google;

  static function twitter(){
    if (!is_object(self::$twitter)) { // if it exists, don't recreate it.
      self::$twitter = new League\OAuth1\Client\Server\Twitter(array(
        'identifier' => $_ENV['TWITTER_IDENTIFIER'],
        'secret' => $_ENV['TWITTER_SECRET'],
        'callback_uri' => URL::to('/auth/twitter/callback'),
      ));
    }
    return self::$twitter;
  }

  static function facebook(){
    if (!is_object(self::$facebook)) { // if it exists, don't recreate it.
      self::$facebook = new League\OAuth2\Client\Provider\Facebook(array(
        'clientId'     => $_ENV['FACEBOOK_APP_ID'],
        'clientSecret' => $_ENV['FACEBOOK_APP_SECRET'],
        'redirectUri'  => URL::to('/auth/facebook/callback'),
      ));
    }
    return self::$facebook;
  }

  static function google(){
    if (!is_object(self::$google)) { // if it exists, don't recreate it.
      self::$google = new League\OAuth2\Client\Provider\Google(array(
        'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
        'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
        'redirectUri'  => URL::to('/auth/google/callback'),
      ));
    }
    return self::$google;
  }

}
