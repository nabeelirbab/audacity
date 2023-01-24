<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderEquitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_equities', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('account_number');
            $table->dateTime('date_at');
            $table->double('pl', 20, 2);
            $table->double('pips', 20, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_equities');
    }
}
