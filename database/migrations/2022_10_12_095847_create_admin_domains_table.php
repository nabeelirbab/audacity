<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_domains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('host');
            $table->unsignedInteger('manager_id');
            $table->timestamps();
            $table->string('schema', 10)->nullable();
            
            $table->foreign('manager_id', 'admin_domains_ibfk_1')->references('id')->on('admin_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_domains');
    }
}
