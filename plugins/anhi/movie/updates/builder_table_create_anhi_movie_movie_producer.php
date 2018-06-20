<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovieProducer extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movie_producer', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('movie_id');
            $table->integer('producer_id');
            $table->primary(['movie_id','producer_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movie_producer');
    }
}
