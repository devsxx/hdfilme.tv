<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovies8 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->integer('top');
            $table->dropColumn('created');
            $table->dropColumn('last_update');
            $table->dropColumn('updated_at');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->dropColumn('top');
            $table->integer('created');
            $table->integer('last_update');
            $table->integer('updated_at');
        });
    }
}
