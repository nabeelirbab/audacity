<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSubscriptionSourceAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_subscription_source_accounts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('email_subscription_id');
            $table->integer('account_id');
            
            $table->foreign('email_subscription_id', 'email_subscription_source_accounts_ibfk_1')->references('id')->on('email_subscriptions')->onDelete('cascade');
            $table->foreign('account_id', 'email_subscription_source_accounts_ibfk_2')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_subscription_source_accounts');
    }
}
