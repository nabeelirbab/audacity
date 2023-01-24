<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingMemberProductAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_member_product_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->integer('account_id');
            $table->timestamps();
            $table->boolean('confirmed')->default(0);
            $table->unsignedInteger('member_id');
            
            $table->unique(['product_id', 'account_id', 'member_id'], 'product_id');
            $table->foreign('product_id', 'licensing_member_product_accounts_ibfk_1')->references('id')->on('licensing_products')->onDelete('cascade');
            $table->foreign('account_id', 'licensing_member_product_accounts_ibfk_2')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('member_id', 'licensing_member_product_accounts_ibfk_3')->references('id')->on('licensing_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_member_product_accounts');
    }
}
