<?php

/*
|---------------------------------------------------------------------
|   carrying legacy urls forward and adding new shortcuts
|---------------------------------------------------------------------
|
|   This route moves legacy urls to laravel ones.
|   there's also a new redirect from channel shortcuts too
|   It replaces the funcitonality of .htaccess from previous version
|
*/

class UrlController extends BaseController
{

  function redirect($slug){

    // if we have a blog with the id slug, redirect to its page
    if (Blog::exists($slug)){
      return Redirect::to('blogger/'.$slug);
    }

    // if we have a channel with the name slug, redirect to it
    if (Channel::exists($slug)) {
      return Redirect::to('posts/'.$slug);
    }

    // if all fails, go to default page
    return Response::make('Sorry, this page does\'nt exist',404);
    //  return Redirect::to('posts/all');

  }
}
