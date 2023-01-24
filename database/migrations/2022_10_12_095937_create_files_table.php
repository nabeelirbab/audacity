<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('path', 1000)->nullable();
            $table->mediumText('data');
            $table->timestamps();
            $table->enum('type', ['srv', 'tpl', 'lib', 'file', 'ex4', 'exe', 'pairs']);
            $table->boolean('is_updated_or_new')->default(1);
            $table->string('name');
            $table->unsignedInteger('manager_id');

            $table->foreign('manager_id', 'files_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
