<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiMovieMovieLinks2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_movie_movie_links', function($table)
        {
            $table->string('url', 255)->nullable()->change();
            $table->string('minute', 255)->default('0')->change();
            $table->dropColumn('episode_section_type');
            $table->dropColumn('server_id');
            $table->dropColumn('title');
            $table->dropColumn('section_type');
            $table->dropColumn('demo');
            $table->dropColumn('data');
            $table->dropColumn('status');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_movie_movie_links', function($table)
        {
            $table->string('url', 255)->nullable(false)->change();
            $table->string('minute', 255)->default(null)->change();
            $table->integer('episode_section_type');
            $table->integer('server_id');
            $table->string('title', 255);
            $table->integer('section_type');
            $table->boolean('demo');
            $table->text('data');
            $table->boolean('status');
        });
    }
}
