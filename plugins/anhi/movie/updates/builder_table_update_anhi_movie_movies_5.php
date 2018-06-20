<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovies5 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->string('link_filmstart', 255)->nullable()->change();
            $table->string('link_imdb', 255)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->string('link_filmstart', 255)->nullable(false)->change();
            $table->string('link_imdb', 255)->nullable(false)->change();
        });
    }
}
