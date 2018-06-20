<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovieEpisodeWatch extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movie_episode_watch', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('movie_id');
            $table->integer('user_id');
            $table->integer('episode');
            $table->integer('watch_time');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movie_episode_watch');
    }
}
