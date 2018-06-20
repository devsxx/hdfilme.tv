<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieProducers extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_producers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('producer_name');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_producers');
    }
}
