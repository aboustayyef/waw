<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class checkTopPosts extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lebaneseBlogs:checkTopPosts';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check For top Posts and publishes them on social media';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
    // get current top post
		$topPosts = Post::getTopPosts('all',12);
    $topPost = $topPosts[0];
    $this->info('Top Post: '.$topPost->post_title);

    // See if it's already in the top posts database
    $exists = topPost::where('top_post_url', $topPost->post_url)->count();
    if ($exists > 0) {
      // post already was top post, abort
      $this->comment('Post already was top');
      return;
    } else {
      if ($topPost->post_visits > 8) {

        # add post to database
        $newTopPost = new topPost;
        $newTopPost->top_post_url = $topPost->post_url;
        $newTopPost->top_post_timestamp_added = time();
        $newTopPost->save();

        # share on facebook
        self::sharePostToFacebook($topPost);

        # share on twitter
        self::sharePostToTwitter($topPost);

        # eventually, email;
      } else {
        $this->comment('Post hasn\'t been clicked 8 times yet');

      }
    }
  }

  static function sharePostToFacebook($post){
    /*
    Reference: http://www.pontikis.net/blog/auto_post_on_facebook_with_php
    postObject has the following attributes: post_image , post_timestamp , post_image_width , post_image_height , post_url , post_title , blog_name , blog_id , col_name, blog_author_twitter_username
    */

    // initialize Facebook class using Facebook App credentials
    // see: https://developers.facebook.com/docs/php/gettingstarted/#install

    // page ID: 625974710801501

    $fbConfig = array(
      'appId' =>  getenv('FACEBOOK_APP_ID'),
      'secret'  =>  getenv('FACEBOOK_APP_SECRET'),
      'fileUpload'  =>  false
    );

    $attribution = '"' . $post->post_title . '" by ' . $post->blog->blog_name;
    $variety_of_messages = array(
    $attribution . ' is now the most popular post on Lebanese Blogs',
    'The most popular blog post on Lebanese Blogs right now is ' . $attribution,
    'A new blog post is now the most popular on Lebanese Blogs: ' . $attribution);

    $messageToShare = $variety_of_messages[rand(0,count($variety_of_messages)-1)];
    $messageToShare = $messageToShare.". Find more top posts at http://lebaneseblogs.com";

    // echo "\nFacebook Message: \n";
    // echo $messageToShare."\n";

    $fb = new Facebook($fbConfig);
    $params = array(
      'access_token'  =>  getenv('FACEBOOK_ACCESS_TOKEN'),
      'message'   =>  $messageToShare,
      'link'      =>  $post->post_url,
    );

    // post to Facebook
    // see: https://developers.facebook.com/docs/reference/php/facebook-api/
    try {
      $ret = $fb->api('/625974710801501/feed', 'POST', $params);
      echo 'Successfully posted to Facebook'."\n";
    } catch(Exception $e) {
      echo $e->getMessage();
    }
  }

  static function sharePostToTwitter($post){
    $twitter_author = $post->blog->blog_author_twitter_username;

    $length_of_twitter_handle = strlen($twitter_author);
    $title_allowance = 59 - $length_of_twitter_handle; // twitter handle + title should be equal to 59 in length to accomodate rest of tweet.
    $title = substr($post->post_title, 0, $title_allowance);

    if ($length_of_twitter_handle > 0) {
      $status = 'New Top Post: '.$title.' by @'.$twitter_author.', '.$post->post_url.'. More at lebaneseblogs.com';
    } else {
      $status = 'New Top Post: '.$title.' '.$post->post_url.'. More at lebaneseblogs.com';
    }
    echo 'Twitter Status: '.$status."\n";

    $twitter = new Twitter(getenv('TWITTER_PUBLISHER_IDENTIFIER'), getenv('TWITTER_PUBLISHER_SECRET'), getenv('TWITTER_PUBLISHER_TOKEN1'), getenv('TWITTER_PUBLISHER_TOKEN2'));
    try {
        $twitter->send($status);
    } catch (TwitterException $e) {
        echo "\nTwitter Error: ", $e->getMessage();
    }
  }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('debugMode', null, InputOption::VALUE_OPTIONAL, 'If in Debug Mode, dont publish', null),
		);
	}

}
