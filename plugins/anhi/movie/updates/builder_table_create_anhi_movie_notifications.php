<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieNotifications extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_notifications', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('user_group_code');
            $table->string('title');
            $table->text('content');
            $table->string('redirect_url');
            $table->boolean('email');
            $table->boolean('browser');
            $table->boolean('web');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->dateTime('expire');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_notifications');
    }
}
