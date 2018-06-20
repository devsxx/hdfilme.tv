<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteAnhiMovieMoviesCategories extends Migration
{
    public function up()
    {
        Schema::dropIfExists('anhi_movie_movies_categories');
    }
    
    public function down()
    {
        Schema::create('anhi_movie_movies_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('movie_id');
            $table->integer('category_id');
            $table->primary(['movie_id','category_id']);
        });
    }
}
