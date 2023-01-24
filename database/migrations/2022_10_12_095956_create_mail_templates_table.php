<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('manager_id')->nullable();
            $table->string('mailable');
            $table->string('subject', 1000)->nullable();
            $table->text('html_template');
            $table->text('text_template')->nullable();
            $table->timestamps();
            $table->string('tag')->nullable();
            
            $table->unique(['mailable', 'manager_id'], 'mailable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_templates');
    }
}
