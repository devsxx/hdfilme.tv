<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddIndex extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_rating', function($table)
        {
            $table->index('user_id');
            $table->index('movie_id');
            $table->unique(['user_id', 'movie_id']);
        });
    }
    
    public function down()
    {
        // Schema::dropIfExists('anhi_movie_actors');
    }
}
