<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSubscriptionUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_subscription_users', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->integer('email_subscription_id');
            $table->boolean('enabled')->default(0);
            $table->timestamps();
            $table->string('email')->nullable();
            $table->integer('id')->primary();
            
            $table->foreign('email_subscription_id', 'email_subscription_users_ibfk_1')->references('id')->on('email_subscriptions')->onDelete('cascade');
            $table->foreign('user_id', 'email_subscription_users_ibfk_2')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_subscription_users');
    }
}
