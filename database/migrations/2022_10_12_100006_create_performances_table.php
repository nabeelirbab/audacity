<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('manager_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('performance_plan_id');
            $table->timestamps();
            $table->boolean('status')->default(0);
            $table->string('slug', 100);
            $table->integer('account_id');
            $table->integer('account_number');
            $table->dateTime('ended_at')->nullable();
            $table->dateTime('started_at')->nullable();
            
            $table->foreign('manager_id', 'performances_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
            $table->foreign('user_id', 'performances_ibfk_2')->references('id')->on('admin_users')->onDelete('cascade');
            $table->foreign('performance_plan_id', 'performances_ibfk_3')->references('id')->on('performance_plans')->onDelete('cascade');
            $table->foreign('account_id', 'performances_ibfk_4')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performances');
    }
}
