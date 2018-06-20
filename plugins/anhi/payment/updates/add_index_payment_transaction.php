<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddIndexPaymentTransaction extends Migration
{
    public function up()
    {
        Schema::table('anhi_payment_transactions', function($table)
        {
            $table->index('user_id');
        });
    }
    
    public function down()
    {
        // Schema::dropIfExists('anhi_movie_actors');
    }
}
