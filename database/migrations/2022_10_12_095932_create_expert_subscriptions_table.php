<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_subscriptions', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedInteger('manager_id');
            $table->string('title');
            $table->integer('count_templates')->default(0);
            $table->boolean('enabled')->default(1);
            $table->integer('expire_days')->nullable();
            $table->timestamps();
            
            $table->foreign('manager_id', 'expert_subscriptions_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expert_subscriptions');
    }
}
