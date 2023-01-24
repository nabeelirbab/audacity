<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsStatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts_stat', function (Blueprint $table) {
            $table->integer('account_number')->primary();
            $table->timestamps();
            $table->integer('nof_closed')->default(0);
            $table->integer('nof_working')->default(0);
            $table->integer('nof_won')->default(0);
            $table->integer('nof_lost')->default(0);
            $table->double('win_ratio', 10, 2)->default(0.00);
            $table->double('total_profit', 20, 2)->default(0.00);
            $table->double('profit_factor', 5, 2)->default(0.00);
            $table->integer('total_weeks')->default(0);
            $table->double('worst_trade_dol', 10, 2)->default(0.00);
            $table->double('best_trade_dol', 10, 2)->default(0.00);
            $table->double('worst_trade_pips', 10, 2)->default(0.00);
            $table->double('best_trade_pips', 10, 2)->default(0.00);
            $table->double('deposit', 20, 2)->default(0.00);
            $table->double('withdrawal', 20, 2)->default(0.00);
            $table->double('total_profit_pips', 20, 2)->default(0.00);
            $table->double('avg_win', 20, 2)->default(0.00);
            $table->double('avg_loss', 20, 2)->default(0.00);
            $table->double('avg_win_pips', 20, 2)->default(0.00);
            $table->double('avg_loss_pips', 20, 2)->default(0.00);
            $table->double('total_lots', 20, 2)->default(0.00);
            $table->double('total_commission', 20, 2)->default(0.00);
            $table->integer('total_longs')->default(0);
            $table->integer('total_shorts')->default(0);
            $table->integer('longs_won')->default(0);
            $table->integer('shorts_won')->default(0);
            $table->integer('total_days')->default(0);
            $table->double('daily_perc', 20, 2)->default(0.00);
            $table->double('total_swap', 20, 2)->default(0.00);
            $table->double('balance', 20, 2)->default(0.00);
            $table->string('currency', 10)->nullable();
            $table->double('profit', 20, 2)->default(0.00);
            $table->double('equity', 20, 3)->default(0.000);
            $table->double('credit', 20, 2)->default(0.00);
            $table->integer('total_months')->default(0);
            $table->double('monthly_perc', 10, 2)->default(0.00);
            $table->double('gain_perc', 10, 2)->default(0.00);
            $table->double('highest_dol', 20, 2)->default(0.00);
            $table->double('drawdown_perc', 20, 2)->default(0.00);
            $table->dateTime('highest_date')->nullable();
            $table->double('min_profit', 20, 2)->default(0.00);
            $table->double('max_profit', 20, 2)->default(0.00);
            $table->integer('nof_pending')->default(0);
            $table->boolean('account_type')->default(0);
            $table->string('broker_company')->nullable();
            $table->string('broker_server')->nullable();
            $table->integer('leverage')->default(100);
            $table->integer('nof_processed')->default(0);
            $table->double('weekly_perc', 10, 2)->default(0.00);
            $table->double('loss_ratio', 10, 2)->default(0.00);
            $table->date('first_trade_day')->nullable();
            $table->double('avg_trades_per_day', 20, 2)->default(0.00);
            $table->double('avg_profit_per_day', 20, 2)->nullable();
            $table->double('current_pace', 20, 2)->default(0.00);
            $table->double('margin')->nullable();
            $table->date('last_trade_day')->nullable();
            
            $table->foreign('account_number', 'stat_accounts_del')->references('account_number')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts_stat');
    }
}
