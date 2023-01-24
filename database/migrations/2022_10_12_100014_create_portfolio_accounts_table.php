<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfolioAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_accounts', function (Blueprint $table) {
            $table->unsignedInteger('portfolio_id');
            $table->integer('account_id');
            $table->timestamps();
            
            $table->primary(['portfolio_id', 'account_id']);
            $table->foreign('portfolio_id', 'portfolio_accounts_ibfk_1')->references('id')->on('portfolios')->onDelete('cascade');
            $table->foreign('account_id', 'portfolio_accounts_ibfk_2')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolio_accounts');
    }
}
