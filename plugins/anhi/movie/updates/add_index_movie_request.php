<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddIndexMovieRequest extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_user_requests', function($table)
        {
            $table->index('user_id');
        });
    }
    
    public function down()
    {
        // Schema::dropIfExists('anhi_movie_actors');
    }
}
