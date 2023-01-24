<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfolioStatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_stat', function (Blueprint $table) {
            $table->unsignedInteger('portfolio_id')->primary();
            $table->integer('nof_closed')->nullable();
            $table->integer('nof_winning')->nullable();
            $table->integer('nof_lossing')->nullable();
            $table->double('win_ratio', 4, 2)->nullable();
            $table->double('net_pl', 10, 2)->nullable();
            $table->double('net_profit', 20, 2)->nullable();
            $table->double('gross_profit', 20, 2)->nullable();
            $table->double('gross_loss', 20, 2)->nullable();
            $table->double('profit_factor', 5, 2)->nullable();
            $table->integer('weeks')->default(0);
            $table->double('worst_trade_dol', 10, 2)->nullable();
            $table->double('best_trade_dol', 10, 2)->nullable();
            $table->double('worst_trade_pips', 10, 2)->nullable();
            $table->double('best_trade_pips', 10, 2)->nullable();
            $table->integer('nof_working')->nullable();
            $table->double('deposit', 8, 2)->nullable();
            $table->double('withdrawal', 20, 2)->default(0.00);
            $table->double('net_profit_pips', 20, 2)->nullable();
            $table->integer('top_nof_closed')->nullable();
            $table->integer('top_nof_winning')->nullable();
            $table->integer('top_nof_lossing')->nullable();
            $table->double('top_win_ratio', 4, 2)->nullable();
            $table->double('top_net_profit', 20, 2)->nullable();
            $table->double('top_net_profit_pips', 20, 2)->nullable();
            $table->double('avg_win', 20, 2)->nullable();
            $table->double('avg_loss', 20, 2)->nullable();
            $table->double('avg_win_pips', 20, 2)->nullable();
            $table->double('avg_loss_pips', 20, 2)->nullable();
            $table->double('total_lots', 20, 2)->nullable();
            $table->double('total_commission', 20, 2)->nullable();
            $table->integer('total_longs')->nullable();
            $table->integer('total_shorts')->nullable();
            $table->integer('longs_won')->nullable();
            $table->integer('shorts_won')->nullable();
            $table->integer('avg_trade_duration')->nullable();
            $table->integer('total_days')->nullable();
            $table->double('avg_daily_return', 20, 2)->nullable();
            $table->double('interest', 20, 2)->nullable();
            $table->double('balance', 20, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->double('profit', 20, 2)->nullable();
            $table->double('equity', 20, 3)->nullable();
            $table->double('credit', 20, 2)->nullable();
            $table->integer('mem')->nullable();
            $table->integer('total_months')->nullable();
            $table->timestamps();
            $table->string('name')->nullable();
            $table->boolean('is_demo')->default(1);
            $table->double('monthly_perc', 10, 2)->nullable();
            $table->double('gain_perc', 10, 2)->nullable();
            $table->double('highest_dol', 20, 2)->nullable();
            $table->double('drawdown_perc', 20, 2)->nullable();
            $table->dateTime('highest_date')->nullable();
            
            $table->foreign('portfolio_id', 'portfolio_stat_ibfk_1')->references('id')->on('portfolios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolio_stat');
    }
}
