<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddIndexMovieLink extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_links', function($table)
        {
            $table->index('movie_id');
        });
    }
    
    public function down()
    {
        // Schema::dropIfExists('anhi_movie_actors');
    }
}
