<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovies6 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->dropColumn('image_name');
            $table->dropColumn('banner');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->string('image_name', 255);
            $table->string('banner', 255);
        });
    }
}
