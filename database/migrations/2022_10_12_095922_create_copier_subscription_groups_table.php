<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSubscriptionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_subscription_groups', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('title', 1000);
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->unsignedInteger('manager_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_subscription_groups');
    }
}
