<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSignalFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_signal_followers', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('signal_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->boolean('copier_enabled')->default(1);
            $table->boolean('email_enabled')->default(0);
            $table->double('fixed_lot', 20, 2)->default(0.00);
            $table->double('lots_multiplier', 20, 2)->default(1.00);
            $table->double('max_lots_per_trade', 20, 2)->default(1000.00);
            $table->double('max_risk', 20, 2)->default(0.00);
            $table->integer('max_open_positions')->default(100);
            $table->integer('risk_type')->default(1);
            $table->double('money_ratio_lots', 10, 2)->default(0.00);
            $table->double('money_ratio_dol', 10, 2)->default(0.00);
            $table->double('min_balance', 20, 2)->default(150.00);
            $table->integer('live_time')->default(0);
            $table->timestamps();
            $table->double('scaling_factor')->default(1);
            $table->boolean('filter_symbol_condition')->default(0);
            $table->string('filter_symbol_values', 1000)->nullable();
            $table->boolean('filter_magic_condition')->default(0);
            $table->string('filter_magic_values', 1000)->nullable();
            $table->boolean('filter_comment_condition')->default(0);
            $table->string('filter_comment_values', 1000)->nullable();
            $table->boolean('reverse_copy')->default(0);
            $table->boolean('copy_existing')->default(1);
            $table->timestamp('copier_enabled_at')->nullable();
            $table->string('signals_email')->nullable();
            $table->boolean('is_past_due')->default(0);
            $table->boolean('dont_copy_sl_tp')->default(0);
            $table->double('sender_sl_offset_pips')->nullable();
            $table->double('sender_tp_offset_pips')->nullable();
            
            $table->unique(['signal_id', 'account_id'], 'unique_scope_account_user');
            $table->foreign('account_id', 'copier_signal_followers_ibfk_2')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('signal_id', 'copier_signal_followers_ibfk_3')->references('id')->on('copier_signals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_signal_followers');
    }
}
