<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGatewayConfigIdToUsersTable extends Migration
{
    public function getTable()
    {
        return config('gateway.user_table_name', 'users');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(self::getTable(), function (Blueprint $table) {
            $table->unsignedBigInteger('gateway_config_id');

            $table
                ->foreign('gateway_config_id')
                ->references('id')
                ->on($this->getTable())
                ->onDelete('cascade');
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
