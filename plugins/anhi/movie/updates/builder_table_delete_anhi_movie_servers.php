<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteAnhiMovieServers extends Migration
{
    public function up()
    {
        Schema::dropIfExists('anhi_movie_servers');
    }
    
    public function down()
    {
        Schema::create('anhi_movie_servers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('server_name', 255);
        });
    }
}
