<?php namespace Anhi\Coupon\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiCouponCoupons extends Migration
{
    public function up()
    {
        Schema::create('anhi_coupon_coupons', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->integer('quantity');
            $table->integer('days');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->boolean('status');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_coupon_coupons');
    }
}
