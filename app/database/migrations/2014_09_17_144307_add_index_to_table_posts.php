<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIndexToTablePosts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('posts', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE posts ADD FULLTEXT post_title (post_title)');
      DB::statement('ALTER TABLE posts ADD FULLTEXT post_title_content (post_title, post_content)');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('posts', function(Blueprint $table)
		{
			$table->dropIndex('post_title');
      $table->dropIndex('post_title_content');
		});
	}

}
