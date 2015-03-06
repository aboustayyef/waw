<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameBlogActiveToBlogRSSCrawlActive extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('blogs', function($table)
    {
        $table->renameColumn('blog_active', 'blog_RSSCrawl_active');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('blogs', function($table)
    {
        $table->renameColumn('blog_RSSCrawl_active','blog_active');
    });
	}

}
