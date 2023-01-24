<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertSubscriptionExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_subscription_experts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('expert_id');
            $table->integer('expert_subscription_id');
            
            $table->foreign('expert_id', 'expert_subscription_experts_ibfk_1')->references('id')->on('experts')->onDelete('cascade');
            $table->foreign('expert_subscription_id', 'expert_subscription_experts_ibfk_2')->references('id')->on('expert_subscriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expert_subscription_experts');
    }
}
