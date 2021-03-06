<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovieUserRequests3 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_user_requests', function($table)
        {
            $table->boolean('send_notification')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movie_user_requests', function($table)
        {
            $table->dropColumn('send_notification');
        });
    }
}
