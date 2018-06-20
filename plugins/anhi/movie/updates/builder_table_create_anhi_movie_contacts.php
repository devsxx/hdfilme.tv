<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieContacts extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_contacts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('email');
            $table->string('name');
            $table->text('content');
            $table->boolean('status')->default(0);
            $table->timestamp('created_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_contacts');
    }
}
