<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopierSubscriptionDestAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copier_subscription_dest_accounts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('copier_subscription_id')->nullable()->index('copiers_scopes_del');
            $table->integer('account_id')->nullable();
            $table->double('fixed_lot', 20, 2)->default(0.00);
            $table->double('lots_multiplier', 20, 2)->default(1.00);
            $table->double('max_lots_per_trade', 20, 2)->default(1000.00);
            $table->double('max_risk', 20, 2)->default(0.00);
            $table->double('price_diff_accepted_pips', 10, 2)->default(10.00);
            $table->integer('max_open_positions')->default(10);
            $table->integer('risk_type')->default(1);
            $table->double('money_ratio_lots', 10, 2)->default(0.00);
            $table->double('money_ratio_dol', 10, 2)->default(0.00);
            $table->double('min_balance', 20, 2)->default(150.00);
            $table->integer('live_time')->default(0);
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->integer('scaling_factor')->default(1);
            $table->boolean('filter_symbol_condition')->default(0);
            $table->string('filter_symbol_values', 1000)->nullable();
            $table->boolean('filter_magic_condition')->default(0);
            $table->string('filter_magic_values', 1000)->nullable();
            $table->boolean('filter_comment_condition')->default(0);
            $table->string('filter_comment_values', 1000)->nullable();
            
            $table->unique(['copier_subscription_id', 'account_id'], 'unique_scope_account_user');
            $table->foreign('account_id', 'copier_subscription_dest_accounts_ibfk_2')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copier_subscription_dest_accounts');
    }
}
