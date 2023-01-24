<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminAuthenticationLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_authentication_log', function (Blueprint $table) {
            $table->id();
            $table->string('authenticatable_type');
            $table->unsignedBigInteger('authenticatable_id');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('login_at')->nullable();
            $table->boolean('login_successful')->default(0);
            $table->timestamp('logout_at')->nullable();
            $table->boolean('cleared_by_user')->default(0);
            $table->json('location')->nullable();
            
            $table->index(['authenticatable_type', 'authenticatable_id'], 'authentication_log_authenticatable_type_authenticatable_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_authentication_log');
    }
}
