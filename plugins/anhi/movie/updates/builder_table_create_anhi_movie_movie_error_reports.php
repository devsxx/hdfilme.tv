<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovieErrorReports extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movie_error_reports', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('movie_id');
            $table->string('movie_name');
            $table->integer('user_id');
            $table->string('user_email');
            $table->string('link');
            $table->string('movie_error');
            $table->string('audio_error');
            $table->text('description');
            $table->integer('episodeepisode');
            $table->dateTime('date_add');
            $table->dateTime('date_process');
            $table->timestamp('updated_at');
            $table->boolean('status');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movie_error_reports');
    }
}
