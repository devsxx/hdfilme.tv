<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovieCat extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movie_cat', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('movie_id');
            $table->integer('category_id');
            $table->primary(['movie_id','category_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movie_cat');
    }
}
