<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieNotificationsUserRead extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_notifications_user_read', function($table)
        {
            $table->string('email')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_notifications_user_read', function($table)
        {
            $table->boolean('email')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
