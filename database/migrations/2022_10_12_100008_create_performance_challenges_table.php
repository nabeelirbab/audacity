<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance_challenges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('performance_plan_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('manager_id');
            $table->timestamps();
            $table->boolean('status')->default(0);
            $table->double('price')->nullable();
            $table->unsignedInteger('performance_id')->nullable();
            $table->unsignedInteger('account_id')->nullable();
            $table->string('last_error', 1000)->nullable();
            
            $table->foreign('performance_plan_id', 'performance_challenges_ibfk_1')->references('id')->on('performance_plans')->onDelete('cascade');
            $table->foreign('user_id', 'performance_challenges_ibfk_2')->references('id')->on('admin_users')->onDelete('cascade');
            $table->foreign('manager_id', 'performance_challenges_ibfk_3')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_challenges');
    }
}
