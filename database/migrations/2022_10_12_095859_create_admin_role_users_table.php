<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminRoleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role_users', function (Blueprint $table) {
            $table->integer('role_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            
            $table->primary(['role_id', 'user_id']);
            $table->foreign('user_id', 'admin_role_users_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_role_users');
    }
}
