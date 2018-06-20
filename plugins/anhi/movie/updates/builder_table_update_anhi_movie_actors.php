<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieActors extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_actors', function($table)
        {
            $table->increments('id')->unsigned(false)->change();
            $table->renameColumn('name', 'actor_name');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_actors', function($table)
        {
            $table->increments('id')->unsigned()->change();
            $table->renameColumn('actor_name', 'name');
        });
    }
}
