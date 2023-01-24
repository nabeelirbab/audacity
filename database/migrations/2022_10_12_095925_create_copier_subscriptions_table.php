<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_subscriptions', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('manager_id');
            $table->string('title', 256);
            $table->boolean('risk_type')->default(0);
            $table->double('fixed_lot', 20, 2)->default(0.00);
            $table->double('max_risk', 20, 2)->default(0.00);
            $table->double('lots_multiplier', 20, 2)->default(1.00);
            $table->double('money_ratio_lots', 20, 2)->default(0.00);
            $table->double('money_ratio_dol', 20, 2)->default(0.00);
            $table->double('max_lots_per_trade', 20, 2)->default(0.10);
            $table->double('price_diff_accepted_pips', 20, 2)->default(10.00);
            $table->integer('max_open_positions')->default(10);
            $table->integer('copier_delay')->default(2000);
            $table->string('memc_servers', 100)->nullable();
            $table->double('min_balance', 20, 2)->default(150.00);
            $table->integer('live_time')->default(0);
            $table->integer('scaling_factor')->default(1);
            $table->timestamps();
            $table->integer('creator_id');
            $table->string('scope_key', 100)->nullable()->unique('scope_key');
            $table->boolean('allow_partial_close')->default(0);
            $table->boolean('lots_formula')->default(0);
            $table->string('pairs_matching', 1000)->nullable();
            $table->integer('copier_subscription_group_id')->nullable();
            $table->boolean('filter_symbol_condition')->default(0);
            $table->string('filter_symbol_values', 1000)->nullable();
            $table->boolean('filter_magic_condition')->default(0);
            $table->string('filter_magic_values', 1000)->nullable();
            $table->boolean('filter_comment_condition')->default(0);
            $table->string('filter_comment_values', 1000)->nullable();
            $table->boolean('is_public')->default(0);
            $table->string('comment', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_subscriptions');
    }
}
