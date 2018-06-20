<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddIndexAds extends Migration
{
    public function up()
    {
        Schema::table('anhi_advertisement_ads', function($table)
        {
            $table->unique('name');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_advertisement_ads', function($table)
        {
            // $table->dropUnique('name');
        });
    }
}
