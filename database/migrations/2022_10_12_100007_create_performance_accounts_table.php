<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance_accounts', function (Blueprint $table) {
            $table->unsignedInteger('performance_id');
            $table->integer('account_id');
            $table->timestamps();
            $table->double('max_daily_loss', 20, 2)->nullable();
            $table->double('max_loss', 20, 2)->nullable();
            $table->dateTime('max_daily_loss_at')->nullable();
            $table->dateTime('max_loss_at')->nullable();
            
            $table->primary(['performance_id', 'account_id']);
            $table->foreign('performance_id', 'performance_accounts_ibfk_1')->references('id')->on('performances')->onDelete('cascade');
            $table->foreign('account_id', 'performance_accounts_ibfk_2')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_accounts');
    }
}
