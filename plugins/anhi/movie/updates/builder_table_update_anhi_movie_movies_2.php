<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovies2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->integer('level_id');
            $table->string('director');
            $table->string('actor');
            $table->string('producer');
            $table->renameColumn('country', 'country_id');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->dropColumn('level_id');
            $table->dropColumn('director');
            $table->dropColumn('actor');
            $table->dropColumn('producer');
            $table->renameColumn('country_id', 'country');
        });
    }
}
