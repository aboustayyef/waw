<?php

class EditController extends \BaseController {
	public function index($what='post', $which=0)
  {
    // first, check if parameters are correct
    if (!in_array($what, ['blog', 'post']) || empty($which)) {
      return Redirect::to('/');
    }
    // then, check if user is signed in
    if (!User::signedIn()) {
      return Redirect::to('/');
    }
    // then, check if user is authorized to edit;
    if ($what == 'blog') {
      if (Blog::exists($which)) {
        $blog = Blog::find($which);
        $owner = $blog->blog_author_twitter_username;
        $user = User::find(User::signedIn())->twitter_username;
        if ($user == 'beirutspring' || $user == $owner) {
          return View::make('static.edit.blog', ['blog'=> $blog]);
        } else {
          // user is not authorized
          return Redirect::to('/');
        }
      } else {
        // Blog Doesn't exist
        return Redirect::to('/');
      }
    } else {
      // $what is 'post'
      if (Post::exists($which)) {
        $post = Post::with('blog')->find($which);
        $owner = $post->blog->blog_author_twitter_username;
        $user = User::find(User::signedIn())->twitter_username;
        if ($user == 'beirutspring' || $user == $owner) {
          return View::make('static.edit.post', ['post' => $post]);
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

  public function submit($what='post', $which=0){
    if ($what == 'blog'):

      $validation = Blog::validate(Input::all());

      if ($validation != 'ok') {
        return Redirect::back()->withErrors($validation)->withInput();
      }

      $file = Input::file('image');
      $thumb = new ProcessUploadedImage($file, $which);
      $fileValidation = $thumb->saveThumb();

      // save data from non-image items in form
      $blog = Blog::find($which);
      $saveSuccess = $blog->editUpdate(Input::all());

      if (!$saveSuccess) {
        return View::make('static.edit.error');
      } else {
        Cache::flush();
        return View::make('static.edit.success');
      }

    elseif ($what == 'post'):

      $validation = Post::validate(Input::all());
      if ($validation != 'ok') {
        return Redirect::back()->withErrors($validation)->withInput();
      }

      // save data
      $post = Post::find($which);
      $saveSuccess = $post->editUpdate(Input::all());
      if (!$saveSuccess ) {
        return View::make('static.edit.error');
      } else {
        return View::make('static.edit.success');
      }
    endif;
  }

}
