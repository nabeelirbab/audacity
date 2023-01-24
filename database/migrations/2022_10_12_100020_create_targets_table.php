<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->increments('id');
            $table->double('max_daily_loss', 20, 2)->nullable();
            $table->double('max_loss', 20, 2)->nullable();
            $table->double('profit', 20, 2)->nullable();
            $table->integer('min_trading_days')->default(10);
            $table->timestamps();
            $table->unsignedInteger('manager_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targets');
    }
}
