<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovieUserRequests extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_user_requests', function($table)
        {
            $table->text('link')->nullable(false)->unsigned(false)->default(null)->change();
            $table->text('process_link')->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movie_user_requests', function($table)
        {
            $table->string('link', 255)->nullable(false)->unsigned(false)->default(null)->change();
            $table->string('process_link', 255)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
