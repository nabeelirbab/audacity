<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSubscriptionSourceAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_subscription_source_accounts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('copier_subscription_id')->index('email_subscription_id');
            $table->integer('account_id')->index('account_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_subscription_source_accounts');
    }
}
