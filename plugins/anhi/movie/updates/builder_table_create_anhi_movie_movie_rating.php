<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovieRating extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movie_rating', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('movie_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movie_rating');
    }
}
