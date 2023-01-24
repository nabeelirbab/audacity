<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSignalEmailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_signal_email_settings', function (Blueprint $table) {
            $table->integer('signal_id')->primary();
            $table->text('template_type_new')->nullable();
            $table->text('template_type_updated')->nullable();
            $table->text('template_type_closed')->nullable();
            $table->boolean('enabled')->default(0);
            
            $table->foreign('signal_id', 'copier_signal_email_settings_ibfk_1')->references('id')->on('copier_signals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_signal_email_settings');
    }
}
