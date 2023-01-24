<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_subscriptions', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('title');
            $table->string('bot_api_token');
            $table->unsignedInteger('manager_id');
            $table->timestamps();
            $table->string('channel_id', 20);
            $table->text('template_signal_new')->nullable();
            $table->text('template_signal_updated')->nullable();
            $table->text('template_signal_closed_profit')->nullable();
            $table->text('template_signal_closed_lost')->nullable();
            $table->text('template_signal_closed_breakeven')->nullable();
            $table->text('template_signal_canceled')->nullable();
            $table->text('template_overview_week')->nullable();
            $table->text('template_overview_month')->nullable();
            $table->text('template_overview_quartal')->nullable();
            $table->text('template_overview_half_year')->nullable();
            $table->text('template_overview_year')->nullable();
            $table->string('tag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_subscriptions');
    }
}
