<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name')->unique('name');
            $table->integer('ex4_file_id');
            $table->unsignedInteger('manager_id');
            $table->timestamps();
            $table->boolean('enabled')->default(1);
            $table->text('template_default');
            $table->integer('automation_file_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experts');
    }
}
