<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from');
            $table->unsignedInteger('to');
            $table->string('title');
            $table->text('message');
            $table->timestamp('read_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
            
            $table->foreign('from', 'admin_messages_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
            $table->foreign('to', 'admin_messages_ibfk_2')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_messages');
    }
}
