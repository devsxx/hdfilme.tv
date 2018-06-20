<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieNotificationsUsers extends Migration
{
    public function up()
    {
        Schema::rename('anhi_movie_notifications_user_read', 'anhi_movie_notifications_users');
        Schema::table('anhi_movie_notifications_users', function($table)
        {
            $table->boolean('email_noti_status')->default(0);
            $table->boolean('browser_noti_status')->default(0);
            $table->boolean('web_noti_status')->default(0);
            $table->dropColumn('email');
            $table->dropColumn('browser');
            $table->dropColumn('web');
        });
    }
    
    public function down()
    {
        Schema::rename('anhi_movie_notifications_users', 'anhi_movie_notifications_user_read');
        Schema::table('anhi_movie_notifications_user_read', function($table)
        {
            $table->dropColumn('email_noti_status');
            $table->dropColumn('browser_noti_status');
            $table->dropColumn('web_noti_status');
            $table->boolean('email')->default(0);
            $table->boolean('browser')->default(0);
            $table->boolean('web')->default(0);
        });
    }
}
