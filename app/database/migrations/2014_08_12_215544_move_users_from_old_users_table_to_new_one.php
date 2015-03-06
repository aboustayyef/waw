<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveUsersFromOldUsersTableToNewOne extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    // This will move users from old users table to new users table,
    // Using data from users_posts and users_blogs

		// first get all user - favorites combinations
    $userfavorites = DB::table('users_blogs')->get();
    foreach ($userfavorites as $key => $userFavorite)
    {
      $facebookID = $userFavorite->user_facebook_id;

      // get user with that facebook id;
      $user = DB::table('users')->where('user_facebook_id', $facebookID)->first();

      // if this user doesn't exist, create a placeholder object
      if (!is_object($user)) {
        $user = new stdClass;
        $user->user_facebook_id = $facebookID;
        $user->user_email = NULL;
        $user->user_first_name = NULL;
        $user->user_last_name = NULL;
        $user->user_gender = NULL;
      }

      // if record is not already in database add it
      if (DB::table('new_users')->where('provider_id', $user->user_facebook_id)->count() == 0 )
      {
        $id = DB::table('new_users')->insertGetId(
            array(
              'provider'  =>  'Facebook',
              'provider_id' =>  $user->user_facebook_id,
              'email_address' => $user->user_email,
              'first_name'  =>  $user->user_first_name,
              'last_name'   =>  $user->user_last_name,
              'gender'      =>  $user->user_gender,
              'updated_timestamp' => (string) time()
              )
        );
      }
    }

    // now do the same thing for user_posts
    $userposts = DB::table('users_posts')->get();
    foreach ($userposts as $key => $userPost)
    {
      $facebookID = $userPost->user_facebook_id;
      // get user with that facebook id;
      $user = DB::table('users')->where('user_facebook_id', $facebookID)->first();

      // if this user doesn't exist, create a placeholder object
      if (!is_object($user)) {
        $user = new stdClass;
        $user->user_facebook_id = $facebookID;
        $user->user_email = NULL;
        $user->user_first_name = NULL;
        $user->user_last_name = NULL;
        $user->user_gender = NULL;
      }

      // if record is not already in database add it
      if (DB::table('new_users')->where('provider_id', $user->user_facebook_id)->count() == 0 )
      {
        $id = DB::table('new_users')->insertGetId(
            array(
              'provider'  =>  'Facebook',
              'provider_id' =>  $user->user_facebook_id,
              'email_address' => $user->user_email,
              'first_name'  =>  $user->user_first_name,
              'last_name'   =>  $user->user_last_name,
              'gender'      =>  $user->user_gender,
              'updated_timestamp' => (string) time()
              )
        );
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
		// empty the records by deleting and recreating the table new_users
    Schema::drop('new_users');
    Schema::create('new_users', function($table)
    {
        $table->increments('id');
        $table->string('provider');
        $table->string('provider_id');
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email_address');
        $table->string('gender');
        $table->timestamps();
    });
	}

}
