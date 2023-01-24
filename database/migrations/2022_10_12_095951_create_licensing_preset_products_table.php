<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingPresetProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_preset_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('preset_id');
            $table->timestamps();
            
            $table->unique(['product_id', 'preset_id'], 'product_id');
            $table->foreign('product_id', 'licensing_preset_products_ibfk_1')->references('id')->on('licensing_products')->onDelete('cascade');
            $table->foreign('preset_id', 'licensing_preset_products_ibfk_2')->references('id')->on('licensing_presets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_preset_products');
    }
}
