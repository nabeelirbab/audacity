<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingProductFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_product_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('path', 1000);
            $table->unsignedInteger('product_id');
            $table->timestamps();
            $table->string('type', 10)->nullable();
            
            $table->unique(['name', 'product_id', 'type'], 'name');
            $table->foreign('product_id', 'licensing_product_files_ibfk_1')->references('id')->on('licensing_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_product_files');
    }
}
