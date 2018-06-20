<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieCountries extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_countries', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->smallInteger('order');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_countries');
    }
}
