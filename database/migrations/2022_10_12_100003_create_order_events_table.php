<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ticket');
            $table->unsignedTinyInteger('state');
            $table->morphs('watcher');
            $table->timestamp('created_at')->nullable();
            $table->unsignedInteger('account_id')->nullable();
            
            $table->unique(['ticket', 'state', 'watcher_type', 'watcher_id'], 'ticket');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_events');
    }
}
