<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBrokerServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_broker_servers', function (Blueprint $table) {
            $table->integer('broker_server_id');
            $table->unsignedInteger('user_id');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->integer('id')->primary();
            $table->boolean('is_default')->default(0);
            $table->string('default_group')->nullable();
            
            $table->foreign('broker_server_id', 'user_broker_servers_ibfk_1')->references('id')->on('broker_servers')->onDelete('cascade');
            $table->foreign('user_id', 'user_broker_servers_ibfk_2')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_broker_servers');
    }
}
