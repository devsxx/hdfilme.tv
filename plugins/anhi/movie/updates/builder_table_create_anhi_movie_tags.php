<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieTags extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->integer('view');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_tags');
    }
}
