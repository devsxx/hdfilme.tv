<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiPaymentTransactions2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_payment_transactions', function($table)
        {
            $table->string('paygate_name');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_payment_transactions', function($table)
        {
            $table->dropColumn('paygate_name');
        });
    }
}
