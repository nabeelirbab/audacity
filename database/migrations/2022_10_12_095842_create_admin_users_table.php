<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('manager_id')->nullable();
            $table->string('username', 190)->unique('admin_users_username_unique');
            $table->string('password', 60);
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->integer('creator_id')->nullable();
            $table->string('email')->nullable();
            $table->string('api_token', 60)->nullable();
            $table->string('theme')->nullable();
            $table->json('trusted_hosts')->nullable();
            $table->boolean('activated')->default(0);
            $table->string('signup_ip', 15)->nullable();
            $table->string('signup_confirmation_ip', 15)->nullable();
            $table->string('signup_sm_ip', 15)->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
