<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSignalPageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_signal_page_settings', function (Blueprint $table) {
            $table->unsignedInteger('signal_id')->primary();
            $table->boolean('hide_open_trades')->default(0);
            $table->boolean('hide_trade_history')->default(0);
            $table->tinyInteger('hide_balance_info')->nullable();
            $table->tinyInteger('hide_broker_info')->nullable();
            $table->tinyInteger('hide_ticket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_signal_page_settings');
    }
}
