<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSignalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_signals', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('manager_id');
            $table->string('slug', 100)->nullable()->unique('scope_key');
            $table->string('title', 256);
            $table->boolean('risk_type')->default(0);
            $table->double('fixed_lot', 20, 2)->default(0.00);
            $table->double('max_risk', 20, 2)->default(0.00);
            $table->double('lots_multiplier', 20, 2)->default(1.00);
            $table->double('money_ratio_lots', 20, 2)->default(0.00);
            $table->double('money_ratio_dol', 20, 2)->default(0.00);
            $table->double('max_lots_per_trade', 20, 2)->default(0.10);
            $table->double('price_diff_accepted_pips', 20, 2)->default(10.00);
            $table->integer('max_open_positions')->default(0);
            $table->double('min_balance', 20, 2)->default(150.00);
            $table->integer('live_time')->default(0);
            $table->integer('scaling_factor')->default(1);
            $table->timestamps();
            $table->integer('creator_id');
            $table->boolean('allow_partial_close')->default(0);
            $table->boolean('lots_formula')->default(0);
            $table->string('pairs_matching', 1000)->nullable();
            $table->boolean('filter_symbol_condition')->default(0);
            $table->string('filter_symbol_values', 1000)->nullable();
            $table->boolean('filter_magic_condition')->default(0);
            $table->string('filter_magic_values', 1000)->nullable();
            $table->boolean('filter_comment_condition')->default(0);
            $table->string('filter_comment_values', 1000)->nullable();
            $table->boolean('is_public')->default(0);
            $table->string('comment', 100)->nullable();
            $table->text('email_template_signal_new')->nullable();
            $table->text('email_template_signal_updated')->nullable();
            $table->text('email_template_signal_closed')->nullable();
            $table->boolean('email_enabled')->default(0);
            $table->boolean('copier_enabled')->default(1);
            $table->text('description')->nullable();
            $table->boolean('copy_existing')->default(1);
            $table->boolean('reverse_copy')->default(0);
            $table->string('telegram_chat_id')->nullable();
            $table->boolean('telegram_enabled')->default(0);
            $table->boolean('dont_copy_sl_tp')->default(0);
            $table->double('sender_sl_offset_pips')->nullable();
            $table->double('sender_tp_offset_pips')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_signals');
    }
}
