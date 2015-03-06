<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeHomepagesOfColumnistsFromTheNational extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//This migration upgrades the database by changing the home pages of Michael Karam and Michael Young on the national
    DB::table('columnists')->where('col_shorthand', 'mkaramtn')->update(array('col_home_page' => 'http://thenational.ae/authors/michael-karam'));
    DB::table('columnists')->where('col_shorthand', 'myoungtn')->update(array('col_home_page' => 'http://thenational.ae/authors/michael-young'));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
    DB::table('columnists')->where('col_shorthand', 'mkaramtn')->update(array('col_home_page' => 'http://www.thenational.ae/apps/pbcs.dll/search?q=*&NavigatorFilter=[Byline:Michael%20Karam]&BuildNavigators=1'));
    DB::table('columnists')->where('col_shorthand', 'myoungtn')->update(array('col_home_page' => 'http://www.thenational.ae/apps/pbcs.dll/search?q=*&NavigatorFilter=[Byline:Michael%20Young]&BuildNavigators=1'));
	}

}
