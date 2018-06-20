<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiPaymentPackages5 extends Migration
{
    public function up()
    {
        Schema::table('anhi_payment_packages', function($table)
        {
            $table->renameColumn('paygate', 'paygate_code');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_payment_packages', function($table)
        {
            $table->renameColumn('paygate_code', 'paygate');
        });
    }
}
