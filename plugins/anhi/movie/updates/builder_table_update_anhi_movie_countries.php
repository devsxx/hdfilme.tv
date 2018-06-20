<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieCountries extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_countries', function($table)
        {
            $table->renameColumn('name', 'country_name');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_countries', function($table)
        {
            $table->renameColumn('country_name', 'name');
        });
    }
}
