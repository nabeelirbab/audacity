<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerServerHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_server_hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('host');
            $table->unsignedInteger('port')->nullable();
            $table->integer('ping')->nullable();
            $table->integer('broker_server_id');
            $table->boolean('is_main')->default(0);
            $table->timestamps();
            $table->boolean('status')->default(0);
            
            $table->foreign('broker_server_id', 'broker_server_hosts_ibfk_1')->references('id')->on('broker_servers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broker_server_hosts');
    }
}
