<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieNotificationsUserRead extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_notifications_user_read', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('notification_id');
            $table->boolean('email');
            $table->boolean('browser');
            $table->boolean('web');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_notifications_user_read');
    }
}
