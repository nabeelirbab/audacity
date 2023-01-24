<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_subscriptions', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedInteger('manager_id');
            $table->string('title');
            $table->timestamps();
            $table->boolean('is_public')->default(0);
            $table->text('template_signal_new')->nullable();
            $table->text('template_signal_updated')->nullable();
            $table->text('template_signal_closed')->nullable();
            
            $table->foreign('manager_id', 'email_subscriptions_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_subscriptions');
    }
}
