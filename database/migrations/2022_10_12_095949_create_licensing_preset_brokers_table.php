<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingPresetBrokersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_preset_brokers', function (Blueprint $table) {
            $table->unsignedInteger('licensing_preset_id')->nullable();
            $table->string('broker_name')->nullable();
            $table->increments('id');
            $table->timestamps();
            
            $table->unique(['licensing_preset_id', 'broker_name'], 'license_preset_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_preset_brokers');
    }
}
