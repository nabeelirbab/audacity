<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsRemovedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_removed', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('account_number');
            $table->string('password', 100);
            $table->string('broker_server_name');
            $table->unsignedInteger('manager_id');
            $table->unsignedInteger('creator_id');
            $table->timestamps();
            $table->tinyInteger('trade_allowed')->nullable();
            $table->tinyInteger('symbol_trade_allowed')->nullable();
            $table->string('last_error', 1000)->nullable();
            $table->boolean('is_live')->nullable();
            $table->tinyInteger('copier_type')->nullable();
            $table->string('api_server_ip', 200)->nullable();
            
            $table->foreign('manager_id', 'accounts_removed_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts_removed');
    }
}
