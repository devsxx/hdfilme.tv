<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiPaymentPaygates extends Migration
{
    public function up()
    {
        Schema::table('anhi_payment_paygates', function($table)
        {
            $table->renameColumn('name', 'paygate_name');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_payment_paygates', function($table)
        {
            $table->renameColumn('paygate_name', 'name');
        });
    }
}
