<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovieDirector extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movie_director', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('movie_id');
            $table->integer('director_id');
            $table->primary(['movie_id','director_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movie_director');
    }
}
