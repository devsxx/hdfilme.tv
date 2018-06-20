<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovieLinks3 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_links', function($table)
        {
            $table->string('minute', 255)->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movie_links', function($table)
        {
            $table->string('minute', 10)->change();
        });
    }
}
