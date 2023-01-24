<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensingMemberBrokersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensing_member_brokers', function (Blueprint $table) {
            $table->string('broker_name')->nullable();
            $table->unsignedInteger('id')->default(0);
            $table->timestamps();
            $table->unsignedInteger('member_id')->nullable();
            
            $table->foreign('member_id', 'licensing_member_brokers_ibfk_1')->references('id')->on('licensing_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licensing_member_brokers');
    }
}
