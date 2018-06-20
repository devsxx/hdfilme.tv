<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovieLinks extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_links', function($table)
        {
            $table->string('episode_show', 10)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movie_links', function($table)
        {
            $table->integer('episode_show')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
