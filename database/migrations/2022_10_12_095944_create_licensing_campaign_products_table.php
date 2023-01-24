<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingCampaignProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_campaign_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('campaign_id');
            
            $table->unique(['product_id', 'campaign_id'], 'product_id');
            $table->foreign('product_id', 'licensing_campaign_products_ibfk_1')->references('id')->on('licensing_products')->onDelete('cascade');
            $table->foreign('campaign_id', 'licensing_campaign_products_ibfk_2')->references('id')->on('licensing_campaigns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_campaign_products');
    }
}
