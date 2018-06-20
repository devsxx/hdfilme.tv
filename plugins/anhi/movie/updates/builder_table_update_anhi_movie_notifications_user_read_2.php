<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieNotificationsUserRead2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_notifications_user_read', function($table)
        {
            $table->boolean('email')->nullable(false)->unsigned(false)->default(0)->change();
            $table->boolean('browser')->default(0)->change();
            $table->boolean('web')->default(0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_notifications_user_read', function($table)
        {
            $table->string('email', 255)->nullable(false)->unsigned(false)->default(null)->change();
            $table->boolean('browser')->default(null)->change();
            $table->boolean('web')->default(null)->change();
        });
    }
}
