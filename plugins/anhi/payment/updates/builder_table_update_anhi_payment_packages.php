<?php namespace Anhi\Payment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAnhiPaymentPackages extends Migration
{
    public function up()
    {
        Schema::table('anhi_payment_packages', function($table)
        {
            $table->renameColumn('day', 'days');
        });
    }
    
    public function down()
    {
        Schema::table('anhi_payment_packages', function($table)
        {
            $table->renameColumn('days', 'day');
        });
    }
}
