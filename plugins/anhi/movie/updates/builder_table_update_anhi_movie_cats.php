<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieCats extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_cats', function($table)
        {
            $table->renameColumn('name', 'category_name');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_cats', function($table)
        {
            $table->renameColumn('category_name', 'name');
        });
    }
}
