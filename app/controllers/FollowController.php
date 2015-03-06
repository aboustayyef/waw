<?php

/**
* Add and remove blogs to followed
*/

class FollowController extends BaseController
{

  function __construct()
  {
    # nothing
  }

  public static function add($blogId){

    $userId = User::signedIn();
    // if no user id (ie no one is signed in)
    if (!$userId) {
      return;
    }

    // check if this blog is already favorited by that user
    $favs = DB::table('blog_user')->where('blog_id',$blogId)->where('user_id',$userId)->count();
    // if not already favorited, add new record
    if ($favs == 0) {
      DB::table('blog_user')->insert(
        array('blog_id' => $blogId, 'user_id' => $userId)
      );
      // Log what just happened;
      $blogName = Blog::find($blogId)->blog_name;
      $userFullName = User::find($userId)->fullName();
      $loggingMessage = "User ($userFullName) has followed blog ($blogName)";
      textFileLogger::log($loggingMessage);
    }
  }

  public static function remove($blogId){

    $userId = User::signedIn();
    // if no user id (ie no one is signed in)
    if (!$userId) {
      return;
    }

    // check if this blog is already favorited by that user
    $favs = DB::table('blog_user')->where('blog_id',$blogId)->where('user_id',$userId)->count();
    // if already favorited, remove record
    if ($favs > 0) {
      DB::table('blog_user')->where('blog_id',$blogId)->where('user_id',$userId)->delete();

      // Log what just happened;
      $blogName = Blog::find($blogId)->blog_name;
      $userFullName = User::find($userId)->fullName();
      $loggingMessage = "User ($userFullName) has unfollowed blog ($blogName)";
      textFileLogger::log($loggingMessage);
    }
  }


}
