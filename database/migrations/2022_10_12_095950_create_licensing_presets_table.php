<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingPresetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_presets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('expiration_days');
            $table->unsignedInteger('max_live_accounts')->default(1);
            $table->unsignedInteger('max_demo_accounts')->default(1);
            $table->boolean('single_pc')->default(1);
            $table->string('broker_name', 100)->nullable();
            $table->unsignedInteger('manager_id');
            $table->timestamps();
            $table->boolean('auto_confirm_new_accounts')->default(1);
            
            $table->foreign('manager_id', 'licensing_presets_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_presets');
    }
}
