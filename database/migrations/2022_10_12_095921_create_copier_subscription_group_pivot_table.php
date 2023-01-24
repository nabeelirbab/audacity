<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSubscriptionGroupPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_subscription_group_pivot', function (Blueprint $table) {
            $table->integer('copier_subscription_id');
            $table->integer('copier_subscription_group_id');
            
            $table->primary(['copier_subscription_id', 'copier_subscription_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_subscription_group_pivot');
    }
}
