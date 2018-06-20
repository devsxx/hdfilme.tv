<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddIndexMovieActor extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_actor', function($table)
        {
            $table->index('actor_id');
            $table->index('movie_id');
        });
    }
    
    public function down()
    {
        // Schema::dropIfExists('anhi_movie_actors');
    }
}
