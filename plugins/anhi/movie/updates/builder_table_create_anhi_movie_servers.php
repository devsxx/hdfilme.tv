<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieServers extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_servers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('server_name');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_servers');
    }
}
