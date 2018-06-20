<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieContacts3 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_contacts', function($table)
        {
            $table->dateTime('processed_at')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_contacts', function($table)
        {
            $table->dateTime('processed_at')->nullable(false)->change();
        });
    }
}
