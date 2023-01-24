<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceStatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance_stat', function (Blueprint $table) {
            $table->unsignedInteger('performance_id')->primary();
            $table->timestamps();
            $table->double('profit', 20, 2)->default(0.00);
            $table->double('max_daily_loss', 20, 2)->default(0.00);
            $table->double('max_loss', 20, 2)->default(0.00);
            $table->dateTime('max_daily_loss_at')->nullable();
            $table->dateTime('max_loss_at')->nullable();
            $table->integer('days_traded')->default(0);
            $table->boolean('hedging_detected')->default(0);
            $table->dateTime('hedging_detected_at')->nullable();
            $table->boolean('sl_not_used')->default(0);
            $table->dateTime('sl_not_used_at')->nullable();
            
            $table->foreign('performance_id', 'performance_stat_ibfk_1')->references('id')->on('performances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_stat');
    }
}
