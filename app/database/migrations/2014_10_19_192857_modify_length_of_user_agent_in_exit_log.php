<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyLengthOfUserAgentInExitLog extends Migration {

	/**
 * Make changes to the database.
 *
 * @return void
 */
public function up()
{
  DB::statement('alter table exit_log modify user_agent varchar(240)');
}

/**
 * Revert the changes to the database.
 *
 * @return void
 */
public function down()
{
  DB::statement('alter table exit_log modify user_agent varchar(120)');
}

}
