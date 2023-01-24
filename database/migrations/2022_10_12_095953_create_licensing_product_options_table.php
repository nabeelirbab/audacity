<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_product_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->nullable();
            $table->string('pkey', 100);
            $table->string('val');
            $table->timestamps();
            $table->boolean('enabled')->default(1);
            
            $table->unique(['product_id', 'pkey'], 'product_id');
            $table->foreign('product_id', 'licensing_product_options_ibfk_1')->references('id')->on('licensing_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_product_options');
    }
}
