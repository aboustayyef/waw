<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersBlogsTableToCoupleWithBlogIdInsteadOfFacebookId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

    // Add the user_id column
		Schema::table('users_blogs', function($table)
    {
        $table->integer('user_id')->unsigned();
    });

    // populate it
    $usersBlogs = DB::table('users_blogs')->get();
    foreach ($usersBlogs as $key => $record) {
      $userID = DB::table('new_users')->where('provider_id', $record->user_facebook_id)->first()->id;
      DB::table('users_blogs')
            ->where('users_blogs_id', $record->users_blogs_id)
            ->update(array('user_id' => $userID));
    }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users_blogs', function($table)
      {
          $table->dropColumn('user_id');
      });
	}

}
