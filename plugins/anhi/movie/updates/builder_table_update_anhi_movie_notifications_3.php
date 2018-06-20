<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieNotifications3 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_notifications', function($table)
        {
            $table->string('to', 255)->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_notifications', function($table)
        {
            $table->string('to', 10)->change();
        });
    }
}
