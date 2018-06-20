<?php namespace Anhi\Coupon\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiCouponCodes extends Migration
{
    public function up()
    {
        Schema::create('anhi_coupon_codes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code');
            $table->string('user_email');
            $table->integer('coupon_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_coupon_codes');
    }
}
