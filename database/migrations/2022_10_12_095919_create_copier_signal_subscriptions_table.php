<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSignalSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_signal_subscriptions', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedInteger('user_id');
            $table->integer('signal_id');
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'signal_id'], 'user_id');
            $table->foreign('signal_id', 'copier_signal_subscriptions_ibfk_1')->references('id')->on('copier_signals')->onDelete('cascade');
            $table->foreign('user_id', 'copier_signal_subscriptions_ibfk_2')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_signal_subscriptions');
    }
}
