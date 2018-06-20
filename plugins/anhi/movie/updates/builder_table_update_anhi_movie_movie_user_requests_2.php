<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovieUserRequests2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_user_requests', function($table)
        {
            $table->dateTime('date_process')->nullable()->change();
            $table->boolean('status')->default(0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movie_user_requests', function($table)
        {
            $table->dateTime('date_process')->nullable(false)->change();
            $table->boolean('status')->default(null)->change();
        });
    }
}
