<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovieUserRequests extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movie_user_requests', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('user_email');
            $table->string('link');
            $table->string('link_index');
            $table->string('process_link');
            $table->integer('type');
            $table->dateTime('date_add');
            $table->dateTime('date_process');
            $table->timestamp('updated_at');
            $table->boolean('status');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movie_user_requests');
    }
}
