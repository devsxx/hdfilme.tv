<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddIndexMovieErrorReport extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_error_reports', function($table)
        {
            $table->index('user_id');
            $table->index('movie_id');
        });
    }
    
    public function down()
    {
        // Schema::dropIfExists('anhi_movie_actors');
    }
}
