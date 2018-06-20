<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovies14 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->integer('slide')->nullable()->change();
            $table->smallInteger('audio')->nullable()->change();
            $table->string('director', 255)->nullable()->change();
            $table->string('actor', 255)->nullable()->change();
            $table->string('producer', 255)->nullable()->change();
            $table->integer('comment_count')->nullable()->change();
            $table->integer('top')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->integer('slide')->nullable(false)->change();
            $table->smallInteger('audio')->nullable(false)->change();
            $table->string('director', 255)->nullable(false)->change();
            $table->string('actor', 255)->nullable(false)->change();
            $table->string('producer', 255)->nullable(false)->change();
            $table->integer('comment_count')->nullable(false)->change();
            $table->integer('top')->nullable(false)->change();
        });
    }
}
