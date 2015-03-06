<?php

/*
|---------------------------------------------------------------------
|   Implementing signin with Facebook, Twitter and Google
|---------------------------------------------------------------------
|
|   This class handles both the authentication component
|   and the callback components of the signin
|
*/

class AuthenticationController extends BaseController
{

  function auth($provider){

    $list_of_providers = ['twitter','facebook','google'];

    // Check if $provider is listed
    if (!in_array($provider, $list_of_providers)) {
      //abort
      return app::abort(404,'Provider Does not exist');
    }

    switch ($provider) {
/*
|---------------------------------------------------------------------
|   TWITTER
|---------------------------------------------------------------------
*/
      case 'twitter':

          // Retrieve temporary credentials
          $temporaryCredentials = AuthenticationServer::twitter()->getTemporaryCredentials();

          // Store credentials in the session, we'll need them later
          Session::put('temporaryCredentials', $temporaryCredentials);
          Session::save();

          // Redirect the resource owner to the login screen on the server.
          return AuthenticationServer::twitter()->authorize($temporaryCredentials);

/*
|---------------------------------------------------------------------
|   FACEBOOK
|---------------------------------------------------------------------
*/
      case 'facebook':

          // Retrieve temporary credentials
          return Redirect::to(AuthenticationServer::facebook()->getAuthorizationUrl());

        break;

/*
|---------------------------------------------------------------------
|   GOOGLE
|---------------------------------------------------------------------
*/
      case 'google':

          // Retrieve temporary credentials
          return Redirect::to(AuthenticationServer::google()->getAuthorizationUrl());

        break;
    }

  }

  function callback($provider){
    switch ($provider) {
/*
|---------------------------------------------------------------------
|   TWITTER Callback
|---------------------------------------------------------------------
*/
      case 'twitter':
        if ((Input::has('oauth_token')) && (Input::has('oauth_verifier'))) {
            // We will now retrieve token credentials from the server
            $tokenCredentials = AuthenticationServer::twitter()->getTokenCredentials(
              Session::get('temporaryCredentials'),
              Input::get('oauth_token'),
              Input::get('oauth_verifier')
            );

            // User is an instance of League\OAuth1\Client\Server\User
            $user = AuthenticationServer::twitter()->getUserDetails($tokenCredentials);

            // Twitter returns full name
            $names = explode(' ', $user->name);
            if (count($names) > 1) {
              $twitterLastName = $names[1];
            } else {
              $twitterLastName = '';
            }
            $userDetails = array(
              'provider'  =>  'Twitter',
              'providerId' => $user->uid,
              'twitterHandle' =>  $user->nickname,
              'firstName'  => $names[0],
              'lastName'  =>  $twitterLastName,
              'email'     =>  $user->email,
              'gender'    =>  null,
              'imageUrl'  =>  $user->urls['profile_image_url']
            );

            return User::register($userDetails);
        } else {
          Session::flash('message', 'Sorry, Could not sign in. Want to try again?');
          return View::make('login');
        }
        break;

/*
|---------------------------------------------------------------------
|   FACEBOOK Callback
|---------------------------------------------------------------------
*/
      case 'facebook':


        if ((Input::has('code'))) {
            // We will now retrieve token credentials from the server
            $tokenCredentials = AuthenticationServer::facebook()->getAccessToken('authorizationCode', [
                'code' => Input::get('code')
            ]);

            // User is an instance of League\OAuth1\Client\Server\User
            $user = AuthenticationServer::facebook()->getUserDetails($tokenCredentials);
            $userDetails = array(
              'provider'  =>  'Facebook',
              'providerId' => $user->uid,
              'twitterHandle' =>  'provider_is_facebook',
              'firstName'  => $user->firstName,
              'lastName'  =>  $user->lastName,
              'email'     =>  $user->email,
              'gender'    =>  null,
              'imageUrl'  =>  $user->imageUrl
            );
            return User::register($userDetails);
        } else {
            Session::flash('message', 'Sorry, Could not sign in. Want to try again?');
            return View::make('login');
        }
        break;

/*
|---------------------------------------------------------------------
|   GOOGLE Callback
|---------------------------------------------------------------------
*/
      case 'google':


        if ((Input::has('code'))) {
            // We will now retrieve token credentials from the server
            $tokenCredentials = AuthenticationServer::google()->getAccessToken('authorizationCode', [
                'code' => Input::get('code')
            ]);

            // User is an instance of League\OAuth1\Client\Server\User
            $user = AuthenticationServer::google()->getUserDetails($tokenCredentials);
            $userDetails = array(
              'provider'  =>  'Google',
              'providerId' => $user->uid,
              'twitterHandle' =>  'provider_is_google',
              'firstName'  => $user->firstName,
              'lastName'  =>  $user->lastName,
              'email'     =>  $user->email,
              'gender'    =>  null,
              'imageUrl'  =>  $user->imageUrl
            );
            return User::register($userDetails);

        } else {
            Session::flash('message', 'Sorry, Could not sign in. Want to try again?');
            return View::make('login');
        }
        break;
    }
  }
}
