<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_managers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->string('login');
            $table->string('password', 25);
            $table->unsignedInteger('manager_id')->nullable();
            $table->timestamps();
            $table->boolean('enabled')->default(1);
            $table->integer('port')->nullable();
            $table->integer('broker_server_id')->nullable();
            $table->string('api_host', 1000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broker_managers');
    }
}
