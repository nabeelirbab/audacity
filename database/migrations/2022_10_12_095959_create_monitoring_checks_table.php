<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoring_checks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('host_id');
            $table->string('type');
            $table->string('status')->nullable();
            $table->boolean('enabled')->default(1);
            $table->text('last_run_value')->nullable();
            $table->json('last_run_output')->nullable();
            $table->timestamp('last_ran_at')->nullable();
            $table->integer('next_run_in_minutes')->nullable();
            $table->timestamp('started_throttling_failing_notifications_at')->nullable();
            $table->json('custom_properties')->nullable();
            $table->timestamps();
            
            $table->foreign('host_id', 'checks_host_id_foreign')->references('id')->on('monitoring_hosts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoring_checks');
    }
}
