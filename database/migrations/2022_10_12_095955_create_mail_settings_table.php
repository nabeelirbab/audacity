<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->unsignedInteger('manager_id')->primary();
            $table->string('transport', 100)->nullable();
            $table->string('host', 1000)->nullable();
            $table->integer('port')->nullable();
            $table->string('encryption', 100)->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->text('main_template')->nullable();
            $table->timestamps();
            
            $table->foreign('manager_id', 'mail_settings_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_settings');
    }
}
