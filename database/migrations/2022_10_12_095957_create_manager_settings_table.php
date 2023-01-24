<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagerSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique('user_id');
            $table->integer('max_copiers')->default(0);
            $table->integer('max_senders')->default(0);
            $table->integer('max_followers')->default(0);
            $table->timestamps();
            $table->boolean('can_edit_brokers')->default(0);
            $table->boolean('create_default_subscription')->default(1);
            
            $table->foreign('user_id', 'manager_settings_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_settings');
    }
}
