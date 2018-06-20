<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieCategories extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_categories', function($table)
        {
            $table->increments('id')->unsigned(false)->change();
            $table->renameColumn('name', 'category_name');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_categories', function($table)
        {
            $table->increments('id')->unsigned()->change();
            $table->renameColumn('category_name', 'name');
        });
    }
}
