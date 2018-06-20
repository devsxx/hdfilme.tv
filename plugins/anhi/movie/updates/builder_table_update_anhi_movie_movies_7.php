<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovies7 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->integer('type')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->boolean('type')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
