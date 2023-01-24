<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('telegramable');
            $table->unsignedInteger('manager_id');
            $table->timestamps();
            $table->text('html_template')->nullable();
            $table->text('markdown_template')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_templates');
    }
}
