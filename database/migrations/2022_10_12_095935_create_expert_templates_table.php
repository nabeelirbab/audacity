<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expert_id');
            $table->integer('account_id')->nullable();
            $table->integer('tpl_file_id')->nullable();
            $table->text('options');
            $table->timestamps();
            $table->boolean('enabled')->default(1);
            $table->string('symbol', 100);
            $table->integer('timeframe')->default(0);
            $table->boolean('is_updated_or_new')->default(1);
            $table->string('automation_file_options', 100)->nullable();
            $table->text('snapshot')->nullable();
            $table->boolean('load_status')->default(0);
            
            $table->unique(['expert_id', 'symbol', 'timeframe', 'account_id'], 'expert_id');
            $table->foreign('account_id', 'expert_templates_ibfk_1')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('expert_id', 'templates_experts_del')->references('id')->on('experts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expert_templates');
    }
}
