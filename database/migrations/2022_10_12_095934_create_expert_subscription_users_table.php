<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertSubscriptionUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_subscription_users', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedInteger('user_id');
            $table->integer('expert_subscription_id')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id', 'expert_subscription_users_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
            $table->foreign('expert_subscription_id', 'expert_subscription_users_ibfk_2')->references('id')->on('expert_subscriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expert_subscription_users');
    }
}
