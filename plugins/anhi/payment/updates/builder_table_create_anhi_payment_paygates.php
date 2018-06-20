<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiPaymentPaygates extends Migration
{
    public function up()
    {
        Schema::create('anhi_payment_paygates', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->string('token');
            $table->boolean('active');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_payment_paygates');
    }
}
