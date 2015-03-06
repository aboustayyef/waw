<?php

class BloggerController extends BaseController{

  function showPosts($nameId){
    // if blog exists
    if (Blog::find($nameId)):
      Session::set('pageKind', 'blogger');
      Session::set('blogger', $nameId);

      // initialize posts counters
      Session::put('postsCounter', 0);
      Session::put('cardsCounter', 0);

      return View::make('posts.main');
    else :
      return Response::make('Blogger Not Found', 404);
    endif;
  }


  function old_showPosts($nameId){
    // if a blog or a column with that id exists,
    if ((Blog::find($nameId))||(Columnist::find($nameId))) {

      // find author name
      if (Blog::find($nameId)) {
        $authorName = Blog::where('blog_id',$nameId)->first()->blog_name;
      } else {
        $authorName = Columnist::where('col_shorthand',$nameId)->first()->col_name;
      }
      // get posts
      $posts = Post::getPostsByBlogger($nameId, 0, 20);

      // Sessions are used for when ajax wants to load more posts
      Session::put('pageKind', 'blogger');
      Session::put('blogger', $nameId);

      $pageTitle = 'Posts by ' . $authorName . '| Lebanese Blogs';
      $pageDescription = "Posts by $authorName , at Lebanese Blogs";

      return View::make('authors.main')->with(array(
        'pageTitle'=>$pageTitle,
        'pageDescription'=> $pageDescription,
        'posts'=>$posts ,
        'from'=>0,
        'to'=>20));
    } else {
        return Response::make('Blogger Not Found', 404);
    }
  }
}
