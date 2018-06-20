<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovies11 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->dropColumn('server_sub_id');
            $table->dropColumn('discount_type');
            $table->dropColumn('discount');
            $table->dropColumn('demo_server_id');
            $table->dropColumn('demo_sub');
            $table->dropColumn('demo_sub_en');
            $table->dropColumn('count_buy');
            $table->dropColumn('level_id');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movies', function($table)
        {
            $table->integer('server_sub_id');
            $table->smallInteger('discount_type');
            $table->decimal('discount', 10, 0);
            $table->smallInteger('demo_server_id');
            $table->text('demo_sub');
            $table->text('demo_sub_en');
            $table->integer('count_buy');
            $table->integer('level_id');
        });
    }
}
