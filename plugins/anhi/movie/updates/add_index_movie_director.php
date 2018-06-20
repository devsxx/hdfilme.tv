<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddIndexMovieDirector extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_director', function($table)
        {
            $table->index('director_id');
            $table->index('movie_id');
        });
    }
    
    public function down()
    {
        // Schema::dropIfExists('anhi_movie_actors');
    }
}
