<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_servers', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('manager_id')->nullable();
            $table->string('ip', 100);
            $table->string('title', 100)->nullable();
            $table->boolean('api_server_status')->default(1);
            $table->integer('max_accounts')->nullable();
            $table->boolean('enabled')->default(1);
            $table->integer('mem')->default(0);
            $table->integer('cpu')->default(0);
            $table->timestamps();
            $table->string('host_name')->nullable();
            
            $table->unique(['ip', 'manager_id'], 'ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_servers');
    }
}
