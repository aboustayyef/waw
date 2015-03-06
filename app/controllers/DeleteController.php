<?php

class DeleteController extends \BaseController {
  public function index($what='post', $which=0)
  {
    // only accept 'post' as second parameter
    if ($what != 'post') {
      return Redirect::to('/');
    }
    // then, check if user is signed in
    if (!User::signedIn()) {
      return Redirect::to('/');
    }
    // then, check if user is authorized to edit;
      if (Post::exists($which)) {
        $post = Post::with('blog')->find($which);
        $owner = $post->blog->blog_author_twitter_username;
        $user = User::find(User::signedIn())->twitter_username;
        if ($user == 'beirutspring' || $user == $owner) {
          $post->delete();
          Cache::flush();
          return View::make('static.edit.deletesuccess');
        } else {
          // user is not authorized
          return Redirect::to('/');
        }
      } else {
       // Post Doesn't exist
        return Redirect::to('/');
      }
  }
}
