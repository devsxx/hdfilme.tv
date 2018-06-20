<?php namespace Anhi\Userprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUserFiles extends Migration
{

    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->integer('money')->nullable();
            $table->integer('last_active')->nullable();
            $table->datetime('expire')->nullable();
            $table->boolean('send_notification')->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function($table) {

            $table->dropColumn([
                'expire',
                'money',
                'last_active',
                'send_notification'
            ]);

        });
    }

}
