<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
            $table->unsignedInteger('manager_id');
            $table->double('initial_deposit', 20, 2)->nullable();
            $table->dateTime('deposited_at')->nullable();
            $table->boolean('is_public')->default(1);
            $table->double('last_balance', 20, 2)->nullable();
            $table->text('description')->nullable();
            
            $table->foreign('manager_id', 'portfolios_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolios');
    }
}
