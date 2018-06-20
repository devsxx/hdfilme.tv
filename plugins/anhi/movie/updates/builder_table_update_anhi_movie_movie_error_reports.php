<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovieErrorReports extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_error_reports', function($table)
        {
            $table->renameColumn('episodeepisode', 'episode');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movie_error_reports', function($table)
        {
            $table->renameColumn('episode', 'episodeepisode');
        });
    }
}
