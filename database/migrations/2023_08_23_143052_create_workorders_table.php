<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workorders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('wo_number');
            $table->string('wo_problem');
            $table->string('wo_problemtype');
            $table->string('wo_description');
            $table->string('wo_customer_po');
            $table->string('wo_asset');
            $table->string('wo_priority');
            $table->string('wo_trade');
            $table->string('wo_category');
            $table->string('wo_tech_nte');
            $table->string('wo_schedule');
            $table->string('wo_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workorders');
    }
};
