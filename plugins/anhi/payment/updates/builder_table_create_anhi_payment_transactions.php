<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiPaymentTransactions extends Migration
{
    public function up()
    {
        Schema::create('anhi_payment_transactions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('transaction_id');
            $table->integer('user_id');
            $table->string('user_email');
            $table->integer('package_id');
            $table->integer('days');
            $table->string('paygate_code');
            $table->string('co_transaction_id');
            $table->decimal('money', 10, 0);
            $table->boolean('status');
            $table->dateTime('date_add');
            $table->dateTime('date_process');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_payment_transactions');
    }
}
