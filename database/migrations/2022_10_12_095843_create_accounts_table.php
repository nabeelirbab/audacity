<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedInteger('user_id');
            $table->integer('account_number')->unique('account_number_uniq');
            $table->tinyInteger('copier_type')->default(2);
            $table->string('broker_server_name')->index('broker_server_name');
            $table->boolean('account_status')->default(0);
            $table->string('api_server_ip', 100)->nullable();
            $table->unsignedInteger('manager_id');
            $table->string('name', 1000)->nullable();
            $table->string('title')->nullable();
            $table->string('password', 256)->nullable();
            $table->string('last_error', 1000)->nullable();
            $table->string('old_api_server_ip', 100)->nullable();
            $table->string('api_version', 20)->nullable();
            $table->boolean('trade_allowed')->default(1);
            $table->boolean('symbol_trade_allowed')->default(1);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('creator_id');
            $table->integer('build')->nullable();
            $table->integer('jfx_mode')->default(0);
            $table->boolean('is_live')->default(0);
            $table->boolean('processing')->default(0);
            $table->unsignedInteger('count_invalid_restarts')->default(0);
            $table->string('suffix', 100)->nullable();
            $table->boolean('wait_restarting')->default(0);
            $table->boolean('collect_equity')->default(0);
            
            $table->foreign('user_id', 'accounts_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
            $table->foreign('user_id', 'accounts_ibfk_2')->references('id')->on('admin_users')->onDelete('cascade');
            $table->foreign('manager_id', 'accounts_ibfk_3')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
