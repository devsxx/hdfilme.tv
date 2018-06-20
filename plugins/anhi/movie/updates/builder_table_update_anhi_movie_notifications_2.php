<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieNotifications2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_notifications', function($table)
        {
            $table->string('to', 10)->nullable();
            $table->dropColumn('email');
            $table->dropColumn('browser');
            $table->dropColumn('web');
            $table->dropColumn('expire');
            $table->dropColumn('user_id');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_notifications', function($table)
        {
            $table->dropColumn('to');
            $table->boolean('email');
            $table->boolean('browser');
            $table->boolean('web');
            $table->dateTime('expire');
            $table->integer('user_id');
        });
    }
}
