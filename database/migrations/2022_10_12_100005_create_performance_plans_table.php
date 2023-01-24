<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformancePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('manager_id');
            $table->string('title')->nullable();
            $table->boolean('enabled')->default(1);
            $table->double('max_daily_loss_perc', 20, 2)->nullable();
            $table->double('max_loss_perc', 20, 2)->nullable();
            $table->double('profit_perc', 20, 2)->nullable();
            $table->integer('min_trading_days')->default(10);
            $table->timestamps();
            $table->double('initial_balance')->default(0);
            $table->boolean('is_public')->default(0);
            $table->string('key')->nullable();
            $table->double('price')->default(0);
            $table->integer('max_trading_days')->nullable();
            $table->integer('leverage')->nullable();
            $table->boolean('check_hedging')->default(0);
            $table->boolean('check_sl')->default(0);
            
            $table->foreign('manager_id', 'performance_plans_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_plans');
    }
}
