<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpdeskTicketCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helpdesk_ticket_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ticket_id');
            $table->unsignedInteger('author_id');
            $table->timestamps();
            $table->text('body')->nullable();
            
            $table->foreign('ticket_id', 'helpdesk_ticket_comments_ibfk_1')->references('id')->on('helpdesk_tickets')->onDelete('cascade');
            $table->foreign('author_id', 'helpdesk_ticket_comments_ibfk_2')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('helpdesk_ticket_comments');
    }
}
