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
        Schema::create('vehicle_verification_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->string('vehicle_registration_number');

            $table->unsignedBigInteger('ds_organization_id')->nullable();
            $table->foreign('ds_organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('ds_center_id')->nullable();
            $table->foreign('ds_center_id')->references('id')->on('locations');
            $table->integer('ds_verification')->nullable()->comment('0: Not Verified, 1: Verified');
            $table->date('ds_verification_date')->nullable();

            $table->unsignedBigInteger('emission_organization_id')->nullable();
            $table->foreign('emission_organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('emission_center_id')->nullable();
            $table->foreign('emission_center_id')->references('id')->on('locations');
            $table->integer('emission_verification')->nullable()->comment('0: Not Verified, 1: Verified');
            $table->date('emission_verification_date')->nullable();

            $table->unsignedBigInteger('insurance_organization_id')->nullable();
            $table->foreign('insurance_organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('insurance_center_id')->nullable();
            $table->foreign('insurance_center_id')->references('id')->on('locations');
            $table->integer('insurance_verification')->nullable()->comment('0: Not Verified, 1: Verified');
            $table->date('insurance_verification_date')->nullable();

            $table->unsignedBigInteger('service_organization_id')->nullable();
            $table->foreign('service_organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('service_center_id')->nullable();
            $table->foreign('service_center_id')->references('id')->on('locations');
            $table->integer('service_verification')->nullable()->comment('0: Not Verified, 1: Verified');
            $table->date('service_verification_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_verification_histories');
    }
};
