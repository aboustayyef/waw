<?php
/*******************************************************************
*  This controller handles redirects to outside of lebanese blogs
*
********************************************************************/
class ExitController extends BaseController
{

  public function lbExit()
  {
    // If no url is set, redirect back
    if (!(Input::Has('url'))) {
      return Redirect::away('/posts/all');
    }

    // get url from parameters
    $encodedUrl = Input::Get('url');
    $url = urldecode($encodedUrl);

    // check if click emanated from app (if token is valid)
    if( (!(Input::Has('token'))) || (Input::get('token') != Session::get('_token'))){
       return Redirect::away($url);
    }

    // if human clicks link for first time, increase counter;
    self::registerExit($url);
    return Redirect::away($url);
  }

  static function registerExit($url){

    // check if post is in database or abort
    $post = Post::where('post_url',$url)->first();
    if (!is_object($post)) {
      return Redirect::away('/posts/all');
    }

    // proceed
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $ip_address = self::getIP();
    $log = new ExitLog;

    // update counter for post (only human, non-repeat user)
    if ((!self::isRobot($u_agent)) && (!$log->has($ip_address, $url))){
            $post->post_visits = $post->post_visits + 1;
            // This only happens if user didn't click on this link before.
            $post->save();
    }

    // update exit log (all users)
    $log->exit_time = time();
    $log->exit_url = $url;
    $log->user_agent = self::isRobot($u_agent) ? '[ROBOT] ' . $u_agent : $u_agent;
    $log->ip_address = $ip_address;
    $log->save();


  }
  static function getIP() {
      $ip;
      if (getenv("HTTP_CLIENT_IP"))
      $ip = getenv("HTTP_CLIENT_IP");
      else if(getenv("HTTP_X_FORWARDED_FOR"))
      $ip = getenv("HTTP_X_FORWARDED_FOR");
      else if(getenv("REMOTE_ADDR"))
      $ip = getenv("REMOTE_ADDR");
      else
      $ip = "UNKNOWN";
      return $ip;
  }

  static function isRobot($user_agent){
    $robotStrings = ['spider','slurp','bot','Bot', 'crawl', 'crawler'];
    foreach ($robotStrings as $key => $robotString) {
      if (str_contains($user_agent, $robotString)) {
        return true;
      }
    }
    return false;
  }

}
