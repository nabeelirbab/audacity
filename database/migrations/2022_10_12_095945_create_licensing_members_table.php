<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('license_key', 100)->unique('license_key');
            $table->unsignedInteger('expiration_days')->nullable();
            $table->integer('max_live_accounts')->default(1);
            $table->integer('max_demo_accounts')->default(1);
            $table->boolean('single_pc')->default(1);
            $table->timestamps();
            $table->date('expired_at')->nullable();
            $table->boolean('auto_confirm_new_accounts')->default(1);
            $table->dateTime('activated_at')->nullable();
            $table->string('MAC', 100)->nullable();
            
            $table->foreign('user_id', 'licensing_members_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_members');
    }
}
