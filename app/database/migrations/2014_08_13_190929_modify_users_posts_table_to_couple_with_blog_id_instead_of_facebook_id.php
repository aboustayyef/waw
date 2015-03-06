<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersPostsTableToCoupleWithBlogIdInsteadOfFacebookId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
  {

    // Add the user_id column
    Schema::table('users_posts', function($table)
    {
        $table->integer('user_id')->unsigned();
    });

    // populate it
    $usersPosts = DB::table('users_posts')->get();
    foreach ($usersPosts as $key => $record) {
      $userID = DB::table('new_users')->where('provider_id', $record->user_facebook_id)->first()->id;
      DB::table('users_posts')
            ->where('users_posts_id', $record->users_posts_id)
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
    Schema::table('users_posts', function($table)
      {
          $table->dropColumn('user_id');
      });
  }

}
