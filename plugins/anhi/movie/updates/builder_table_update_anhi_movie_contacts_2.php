<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieContacts2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_contacts', function($table)
        {
            $table->timestamp('updated_at');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_contacts', function($table)
        {
            $table->dropColumn('updated_at');
        });
    }
}
