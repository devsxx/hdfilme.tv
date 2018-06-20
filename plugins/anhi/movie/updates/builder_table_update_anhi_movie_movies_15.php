<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovies15 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->string('meta_desc', 255)->nullable()->change();
            $table->string('meta_key', 255)->nullable()->change();
            $table->integer('view')->nullable()->change();
            $table->integer('view_date_day')->nullable()->change();
            $table->integer('view_in_day')->nullable()->change();
            $table->integer('view_in_week')->nullable()->change();
            $table->integer('view_in_months')->nullable()->change();
            $table->integer('rate_total')->nullable()->default(0)->change();
            $table->integer('rate_count')->nullable()->default(0)->change();
            $table->integer('feature')->nullable()->change();
            $table->integer('expire')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->string('meta_desc', 255)->nullable(false)->change();
            $table->string('meta_key', 255)->nullable(false)->change();
            $table->integer('view')->nullable(false)->change();
            $table->integer('view_date_day')->nullable(false)->change();
            $table->integer('view_in_day')->nullable(false)->change();
            $table->integer('view_in_week')->nullable(false)->change();
            $table->integer('view_in_months')->nullable(false)->change();
            $table->integer('rate_total')->nullable(false)->default(null)->change();
            $table->integer('rate_count')->nullable(false)->default(null)->change();
            $table->integer('feature')->nullable(false)->change();
            $table->integer('expire')->nullable(false)->change();
        });
    }
}
