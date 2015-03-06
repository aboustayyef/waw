<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveColumnistsToBlogs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$columnists = Columnist::all();
    foreach ($columnists as $key => $columnist) {

      // find the date of the last post by columnist;
      $lastPostDatestamp = Post::Where('blog_id',$columnist->col_shorthand)->orderBy('post_timestamp','desc')->first()->post_timestamp;

      $blog = new Blog;
      $blog->blog_active = 0 ; // so that RSS crawler doesn't recognize them;
      $blog->blog_last_post_timestamp = $lastPostDatestamp;
      $blog->blog_id = $columnist->col_shorthand ;
      $blog->blog_author_twitter_username = $columnist->col_author_twitter_username ;
      $blog->blog_name = $columnist->col_name ;
      $blog->blog_description = $columnist->col_description;
      $blog->blog_tags = $columnist->col_tags;
      $blog->blog_url = $columnist->col_home_page;
      try {
        $blog->save();
      } catch (Exception $e) {
        echo 'Could not save Columnist ' . $columnist->col_name ."\n";
      }
    }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$columnists = Columnist::all();
    foreach ($columnists as $key => $columnist) {
      $blog = Blog::where('blog_id',$columnist->col_shorthand);
      try {
        $blog->delete();
      } catch (Exception $e) {
        echo 'Could not delete Columnist ' . $columnist->col_name ."\n";
      }
    }
	}

}
