<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_servers', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 120)->unique('index_server_name');
            $table->binary('srv_file');
            $table->string('suffix', 10)->nullable();
            $table->boolean('is_updated_or_new')->default(1);
            $table->integer('gmt_offset')->default(0);
            $table->timestamps();
            $table->string('srv_file_path')->nullable();
            $table->boolean('api_or_manager')->default(0);
            $table->mediumText('human_readable')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('is_default')->default(0);
            $table->string('main_server_host')->nullable();
            $table->integer('main_server_port')->nullable();
            $table->tinyInteger('type')->default(4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broker_servers');
    }
}
