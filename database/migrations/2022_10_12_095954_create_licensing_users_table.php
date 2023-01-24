<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique('user_id');
            $table->unsignedInteger('affiliate_id')->nullable();
            $table->unsignedInteger('campaign_id')->nullable();
            $table->string('reference_source')->nullable();
            
            $table->foreign('user_id', 'licensing_users_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_users');
    }
}
