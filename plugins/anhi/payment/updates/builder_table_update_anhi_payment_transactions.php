<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiPaymentTransactions extends Migration
{
    public function up()
    {
        Schema::table('anhi_payment_transactions', function($table)
        {
            $table->timestamp('created_at');
            $table->dateTime('processed_at');
            $table->dropColumn('co_transaction_id');
            $table->dropColumn('date_add');
            $table->dropColumn('date_process');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_payment_transactions', function($table)
        {
            // $table->dropColumn('created_at');
            // $table->dropColumn('processed_at');
            // $table->string('co_transaction_id', 255);
            // $table->dateTime('date_add');
            // $table->dateTime('date_process');
        });
    }
}
