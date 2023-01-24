<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_orders', function (Blueprint $table) {
            $table->integer('ticket');
            $table->integer('account_number')->index('account_number');
            $table->boolean('status')->default(0)->index('status');
            $table->string('symbol', 10)->nullable();
            $table->integer('type')->nullable()->index('index_system_orders_on_type');
            $table->string('type_str', 20)->nullable();
            $table->double('lots', 20, 2)->nullable();
            $table->double('price')->nullable();
            $table->double('stoploss', 10, 5)->default(0.00000);
            $table->double('takeprofit', 10, 5)->default(0.00000);
            $table->timestamp('time_close')->nullable()->index('index_system_orders_on_time_close');
            $table->double('price_close')->nullable();
            $table->double('pl', 20, 2)->nullable()->index('index_system_orders_on_pl');
            $table->timestamp('time_open')->nullable();
            $table->timestamp('time_last_action')->nullable();
            $table->integer('magic')->nullable()->index('index_system_orders_on_magic');
            $table->double('pips', 20, 2)->default(0.00);
            $table->double('swap', 10, 5)->nullable();
            $table->integer('last_error_code')->default(0);
            $table->string('last_error', 2500)->nullable();
            $table->timestamp('time_created')->useCurrent();
            $table->double('commission', 20, 2)->nullable();
            $table->string('comment', 200)->nullable();
            $table->double('sl_pips', 10, 2)->default(0.00);
            $table->double('sl_dol', 10, 2)->default(0.00);
            $table->timestamps();
            $table->integer('strategy_id')->nullable();
            
            $table->unique(['ticket', 'account_number'], 'index_system_orders_on_system_id_and_ticket_and_master_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_orders');
    }
}
