<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('manager_id')->nullable();
            $table->timestamps();
            $table->string('version', 10)->default('0.1');
            
            $table->unique(['key', 'manager_id'], 'key');
            $table->foreign('manager_id', 'licensing_products_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_products');
    }
}
