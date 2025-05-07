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
        Schema::create('vehicle_insurances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->string('vehicle_registration_number');
            $table->string('policy_no');
            $table->date('valid_from');
            $table->date('valid_to');
            $table->unsignedBigInteger('insurance_organization_id');
            $table->foreign('insurance_organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('insurance_center_id');
            $table->foreign('insurance_center_id')->references('id')->on('locations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_insurances');
    }
};
