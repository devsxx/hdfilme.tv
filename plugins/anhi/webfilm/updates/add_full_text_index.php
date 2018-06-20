<?php namespace Anhi\Userprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use DB;

class AddFullTextSearch extends Migration
{
	public function up()
    {
    	DB::statement('
    		alter table anhi_movie_movies add fulltext(name,english_name,actor,director,producer)');
    }

    public function down()
    {
    }
}