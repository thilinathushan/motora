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
        Schema::create('vehicle_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->string('vehicle_registration_number');
            $table->string('current_milage')->comment('in kilometers');
            $table->string('next_service_milage')->comment('in kilometers');
            $table->string('is_engine_oil_change');
            $table->string('is_engine_oil_filter_change');
            $table->string('is_brake_oil_change');
            $table->string('is_brake_pad_change');
            $table->string('is_transmission_oil_change');
            $table->string('is_deferential_oil_change');
            $table->string('is_headlights_okay');
            $table->string('is_signal_light_okay');
            $table->string('is_brake_lights_okay');
            $table->string('is_air_filter_change');
            $table->string('is_radiator_oil_change');
            $table->string('is_ac_filter_change');
            $table->string('ac_gas_level');
            $table->string('is_tyre_air_pressure_ok');
            $table->string('tyre_condition');
            $table->unsignedBigInteger('vehicle_service_organization_id');
            $table->foreign('vehicle_service_organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('vehicle_service_center_id');
            $table->foreign('vehicle_service_center_id')->references('id')->on('locations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_services');
    }
};
