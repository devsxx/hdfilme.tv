<?php namespace Anhi\Advertisement\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiAdvertisementAds extends Migration
{
    public function up()
    {
        Schema::table('anhi_advertisement_ads', function($table)
        {
            $table->integer('width');
            $table->integer('height');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_advertisement_ads', function($table)
        {
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
}
