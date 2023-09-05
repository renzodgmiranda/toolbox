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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('cus_name');
            $table->string('cus_store_number')->nullable();
            $table->string('cus_facility_coordinator')->nullable();
            $table->string('cus_facility_coordinator_contact')->nullable();
            $table->string('cus_district_coordinator')->nullable();
            $table->string('cus_district_coordinator_contact')->nullable();
            $table->float('cus_lat')->nullable();
            $table->float('cus_long')->nullable();
            $table->string('cus_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
