<?php namespace Anhi\Advertisement\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiAdvertisementAds extends Migration
{
    public function up()
    {
        Schema::create('anhi_advertisement_ads', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('html');
            $table->boolean('active');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_advertisement_ads');
    }
}
