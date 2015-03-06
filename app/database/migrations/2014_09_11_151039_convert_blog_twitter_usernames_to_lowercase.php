<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertBlogTwitterUsernamesToLowercase extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$blogs=Blog::all();
    foreach ($blogs as $key => $blog) {
      $blog->blog_author_twitter_username = strtolower($blog->blog_author_twitter_username);
      $blog->save();
    }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
