<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramSubscriptionSourceAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_subscription_source_accounts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('telegram_subscription_id')->index('telegram_subscription_source_accounts_ibfk_2');
            $table->integer('account_id');
            
            $table->foreign('account_id', 'telegram_subscription_source_accounts_ibfk_1')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_subscription_source_accounts');
    }
}
