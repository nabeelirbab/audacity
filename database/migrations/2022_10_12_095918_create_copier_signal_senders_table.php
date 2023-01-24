<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSignalSendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_signal_senders', function (Blueprint $table) {
            $table->integer('signal_id');
            $table->integer('account_id');
            $table->timestamps();
            
            $table->unique(['signal_id', 'account_id'], 'email_subscription_id');
            $table->foreign('signal_id', 'copier_signal_senders_ibfk_1')->references('id')->on('copier_signals')->onDelete('cascade');
            $table->foreign('account_id', 'copier_signal_senders_ibfk_2')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_signal_senders');
    }
}
