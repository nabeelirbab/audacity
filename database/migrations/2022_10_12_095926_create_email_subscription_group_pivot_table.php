<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSubscriptionGroupPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_subscription_group_pivot', function (Blueprint $table) {
            $table->integer('email_subscription_id');
            $table->integer('email_subscription_group_id');
            
            $table->primary(['email_subscription_id', 'email_subscription_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_subscription_group_pivot');
    }
}
