<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('manager_id');
            $table->string('title', 1000);
            $table->text('description')->nullable();
            $table->string('slug')->nullable()->unique('slug');
            $table->dateTime('expired_at');
            $table->integer('max_live_accounts')->default(1);
            $table->integer('max_demo_accounts')->default(1);
            $table->boolean('single_pc')->default(1);
            $table->timestamps();
            $table->boolean('auto_confirm_new_accounts')->default(1);
            
            $table->foreign('manager_id', 'licensing_campaigns_ibfk_1')->references('id')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_campaigns');
    }
}
