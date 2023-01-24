<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpdeskTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helpdesk_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id');
            $table->timestamps();
            $table->text('description');
            $table->string('subject')->nullable();
            $table->nullableMorphs('regarding');
            $table->boolean('status')->default(1);
            $table->boolean('priority')->default(1);
            $table->unsignedInteger('manager_id');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->unsignedInteger('last_commentator_id')->nullable();
            
            $table->foreign('author_id', 'helpdesk_tickets_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
            $table->foreign('manager_id', 'helpdesk_tickets_ibfk_2')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('helpdesk_tickets');
    }
}
