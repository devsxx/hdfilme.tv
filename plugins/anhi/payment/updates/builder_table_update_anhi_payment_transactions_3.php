<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiPaymentTransactions3 extends Migration
{
    public function up()
    {
        Schema::table('anhi_payment_transactions', function($table)
        {
            $table->dateTime('processed_at')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_payment_transactions', function($table)
        {
            $table->dateTime('processed_at')->nullable(false)->change();
        });
    }
}
