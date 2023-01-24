<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance_results', function (Blueprint $table) {
            $table->unsignedInteger('performance_id')->primary();
            $table->double('max_daily_loss', 20, 2)->nullable();
            $table->double('max_loss', 20, 2)->nullable();
            $table->double('profit', 20, 2)->nullable();
            $table->integer('min_trading_days')->default(0);
            $table->timestamps();
            
            $table->foreign('performance_id', 'performance_results_ibfk_1')->references('id')->on('performances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_results');
    }
}
