<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovieWatchLater extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movie_watch_later', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('movie_id');
            $table->integer('user_id');
            $table->timestamp('created_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movie_watch_later');
    }
}
