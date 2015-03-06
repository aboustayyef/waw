<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameManyToManyTablesToFitLaravelConventions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::rename('users_blogs', 'blog_user');
    Schema::rename('users_posts', 'post_user');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::rename('blog_user', 'users_blogs');
    Schema::rename('post_user', 'users_posts');
	}

}
