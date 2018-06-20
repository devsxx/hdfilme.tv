<?php namespace Anhi\Advertisement\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiAdvertisementAds2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_advertisement_ads', function($table)
        {
            $table->boolean('mobile');
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_advertisement_ads', function($table)
        {
            $table->dropColumn('mobile');
            $table->integer('width');
            $table->integer('height');
        });
    }
}
