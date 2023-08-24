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
            $table->string('wo_number')->nullable();
            $table->string('wo_problem')->nullable();
            $table->string('wo_problem_type')->nullable();
            $table->string('wo_description')->nullable();
            $table->string('wo_customer_po')->nullable();
            $table->string('wo_asset')->nullable();
            $table->string('wo_priority')->nullable();
            $table->string('wo_trade')->nullable();
            $table->string('wo_category')->nullable();
            $table->string('wo_tech_nte')->nullable();
            $table->string('wo_schedule')->nullable();
            $table->string('wo_status')->nullable();
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
