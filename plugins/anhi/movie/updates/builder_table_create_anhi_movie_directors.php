<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieDirectors extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_directors', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('director_name');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_directors');
    }
}
