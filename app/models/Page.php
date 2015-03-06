<?php

/**
 * This model is used to get Website content (title, description, initial content..)
 */

  class Page
  {
    public static  function getTitle(){

      $pagekind = Session::get('pageKind');

      if ((empty($pagekind)) || ($pagekind == 'allPosts')) {
        return 'Lebanese Blogs | Latest Posts from the Best Blogs';
      }

      if ($pagekind == 'channel') {
        $channelDescription = Channel::description(Session::get('channel'));
        return "Top $channelDescription blogs in Lebanon | Lebanese Blogs";
      }

      if ($pagekind == 'following') {
        return 'Posts by Blogs I\'m following';
      }

      if ($pagekind == 'news') {
        return 'Latest Lebanon News | Lebanese Blogs';
      }

      if ($pagekind == 'blogger') {
        $bloggerDetails = Blog::find(Session::get('blogger'));
        $blogName = $bloggerDetails->blog_name;
        return $blogName.' At Lebanese Blogs';
      }

    }

    public static  function getDescription(){

      $pagekind = Session::get('pageKind');

      if ((empty($pagekind)) || ($pagekind == 'allPosts')) {
        return 'The best place to discover, read and organize Lebanon\'s top blogs';
      }

      if ($pagekind == 'channel') {
        $channelDescription = Channel::description(Session::get('channel'));
        return "Top $channelDescription posts in Lebanon";
      }

      if ($pagekind == 'folowing') {
        return 'latest posts by blogs I am following';
      }

      if ($pagekind == 'blogger') {
        $bloggerDetails = Blog::find(Session::get('blogger'));
        $blogName = $bloggerDetails->blog_name;
        return 'Latest posts by ' . $blogName . ' At Lebanese Blogs';
      }
    }

    public static function getPosts($from=0, $amount=20){

      $pagekind = Session::get('pageKind');
      $user =User::signedIn();

      if (!empty($user)) {
        $userId = User::signedIn();
      }

      if ( $pagekind == 'following'):

        $posts = Post::getFollowedPosts($from, $amount);
        return $posts;

      elseif ( $pagekind == 'liked'):

        $posts = Post::getSavedPosts($from, $amount);
        return $posts;

      elseif ( $pagekind == 'allPosts'):

        $posts = Post::getPosts('all', $from, $amount);
        return $posts;

      elseif ( $pagekind == 'blogger'):
        $bloggerId = Session::get('blogger');
        $posts = Post::getPostsByBlogger($bloggerId, $from, $amount);
        return $posts;

      elseif ( $pagekind == 'channel'):
        $channel = Session::get('channel');
        $posts = Post::getPosts($channel, $from, $amount);
        return $posts;

      elseif ( $pagekind == 'searchResults'):
        $posts = Post::getPostsFromSearchResults($from, $amount);
        return $posts;
      endif;
    }
  }
