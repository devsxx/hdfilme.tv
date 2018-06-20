<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieNotifications extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_notifications', function($table)
        {
            $table->integer('user_id');
            $table->dropColumn('user_group_code');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_notifications', function($table)
        {
            $table->dropColumn('user_id');
            $table->string('user_group_code', 255);
        });
    }
}
