<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldsToNewUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add the columns to new users
    Schema::table('new_users', function($table)
    {
        $table->integer('last_visit_timestamp')->nullable();
        $table->string('image_url')->nullable();
        $table->integer('visit_count')->nullable();
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('new_users', function($table)
      {
          $table->dropColumn('last_visit_timestamp');
          $table->dropColumn('image_url');
          $table->dropColumn('visit_count');
      });
	}

}
