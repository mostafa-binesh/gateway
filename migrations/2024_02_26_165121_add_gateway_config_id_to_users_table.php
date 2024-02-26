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
            // $table->text('description')->after('ip')->nullable();
            // $table->engine = "innoDB";
            // $table->unsignedBigInteger('id', true);

            $table->unsignedBigInteger('gateway_config_id');
            // $table->unsignedBigInteger('user_id');

            $table
                ->foreign('gateway_config_id')
                ->references('id')
                ->on(CreateGatewayConfigsTable::getTable())
                ->onDelete('cascade');
            // $table
            //     ->foreign('user_id')
            //     ->references('id')
            //     ->on($this->getUserTable())
            //     ->onDelete('cascade');
            // $table->string('name');
            // $table->text('config');
            // $table->boolean('active');
            // $table->enum('port', (array) Enum::getIPGs());
            // $table->decimal('price', 15, 2);
            // $table->string('tracking_code', 50)->nullable();
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
