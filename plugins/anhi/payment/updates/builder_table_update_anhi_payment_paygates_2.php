<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiPaymentPaygates2 extends Migration
{
    public function up()
    {
        Schema::table('anhi_payment_paygates', function($table)
        {
            $table->dropColumn('token');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_payment_paygates', function($table)
        {
            $table->string('token', 255);
        });
    }
}
