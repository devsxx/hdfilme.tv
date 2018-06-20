<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieContacts extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_contacts', function($table)
        {
            $table->dateTime('processed_at');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_contacts', function($table)
        {
            $table->dropColumn('processed_at');
        });
    }
}
