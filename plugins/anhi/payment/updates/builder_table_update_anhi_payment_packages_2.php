<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiPaymentPackages2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_payment_packages', function($table)
        {
            $table->renameColumn('pay_gate', 'paygate');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_payment_packages', function($table)
        {
            $table->renameColumn('paygate', 'pay_gate');
        });
    }
}
