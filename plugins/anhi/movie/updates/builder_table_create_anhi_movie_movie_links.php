<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovieLinks extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movie_links', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('movie_id');
            $table->integer('episode');
            $table->integer('episode_show');
            $table->integer('episode_section_type');
            $table->integer('server_id');
            $table->string('url');
            $table->string('title');
            $table->integer('minute');
            $table->integer('section_type');
            $table->boolean('demo');
            $table->text('data');
            $table->boolean('status');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movie_links');
    }
}
