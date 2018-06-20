<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteAnhiMovieCats extends Migration
{
    public function up()
    {
        Schema::dropIfExists('anhi_movie_cats');
    }
    
    public function down()
    {
        Schema::create('anhi_movie_cats', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('category_name', 255);
        });
    }
}
