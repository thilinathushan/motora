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
        Schema::create('vehicle_emissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->string('vehicle_registration_number');
            $table->string('odometer');
            $table->string('rpm_idle')->nullable();
            $table->string('hc_idle')->nullable();
            $table->string('co_idle')->nullable();
            $table->string('rpm_2500')->nullable();
            $table->string('hc_2500')->nullable();
            $table->string('co_2500')->nullable();
            $table->string('average_k_factor')->nullable();
            $table->string('overall_status');
            $table->date('valid_from');
            $table->date('valid_to');
            $table->unsignedBigInteger('emission_test_organization_id');
            $table->foreign('emission_test_organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('emission_test_center_id');
            $table->foreign('emission_test_center_id')->references('id')->on('locations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_emissions');
    }
};
