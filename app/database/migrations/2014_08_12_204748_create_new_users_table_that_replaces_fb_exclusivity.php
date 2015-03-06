<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewUsersTableThatReplacesFbExclusivity extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('new_users', function($table)
    {
        $table->increments('id');
        $table->string('provider');
        $table->string('provider_id');
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->string('email_address')->nullable();
        $table->string('gender')->nullable();
        $table->string('updated_timestamp');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('new_users');
	}

}
