<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovieTag extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movie_tag', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('movie_id');
            $table->integer('tag_id');
            $table->primary(['movie_id','tag_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movie_tag');
    }
}
