<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiPaymentPackages extends Migration
{
    public function up()
    {
        Schema::create('anhi_payment_packages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('package_name');
            $table->string('pay_gate');
            $table->decimal('price', 10, 0);
            $table->integer('day');
            $table->string('description');
            $table->boolean('active');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_payment_packages');
    }
}
