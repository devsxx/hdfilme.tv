<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiPaymentPackages6 extends Migration
{
    public function up()
    {
        Schema::table('anhi_payment_packages', function($table)
        {
            $table->decimal('price', 10, 2)->change();
        });
    }
    
    public function down()
    {
        Schema::table('anhi_payment_packages', function($table)
        {
            $table->decimal('price', 10, 0)->change();
        });
    }
}
