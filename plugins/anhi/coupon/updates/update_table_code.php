<?php namespace Anhi\Coupon\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateTableCode extends Migration
{
    public function up()
    {
        Schema::table('anhi_coupon_codes', function($table)
        {
            $table->string('user_email')->nullable()->change();
        });
    }
    
    public function down()
    {
    }
}
