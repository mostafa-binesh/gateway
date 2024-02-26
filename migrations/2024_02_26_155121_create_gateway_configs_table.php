<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewayConfigsTable extends Migration
{
    public static function getTable()
    {
        return config('gateway.gateway_configs_table_name', 'gateway_configs');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::getTable(), function (Blueprint $table) {
			$table->id();
			$table->string('port');
			$table->string('name');
            $table->text('config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(self::getTable());
    }
}
