<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovies16 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->text('desc')->nullable()->change();
            $table->string('friendly_url', 255)->nullable()->change();
            $table->string('site_title', 255)->nullable()->change();
            $table->decimal('rate', 5, 2)->nullable(false)->unsigned(false)->default(0.0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->text('desc')->nullable(false)->change();
            $table->string('friendly_url', 255)->nullable(false)->change();
            $table->string('site_title', 255)->nullable(false)->change();
            $table->double('rate', 10, 0)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
