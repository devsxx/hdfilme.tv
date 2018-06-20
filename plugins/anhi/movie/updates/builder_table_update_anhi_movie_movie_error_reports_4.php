<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovieErrorReports4 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_error_reports', function($table)
        {
            $table->integer('movie_type')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movie_error_reports', function($table)
        {
            $table->dropColumn('movie_type');
        });
    }
}
