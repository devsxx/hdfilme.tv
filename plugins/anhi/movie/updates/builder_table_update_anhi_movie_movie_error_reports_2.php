<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovieErrorReports2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_error_reports', function($table)
        {
            $table->dropColumn('date_process');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movie_error_reports', function($table)
        {
            $table->dateTime('date_process');
        });
    }
}
