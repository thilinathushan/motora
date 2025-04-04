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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('chassis_number')->unique();
            $table->text('current_owner_address_idNo');
            $table->text('conditions_special_notes');
            $table->string('absolute_owner');
            $table->string('engine_no')->unique();
            $table->string('cylinder_capacity');
            $table->string('class_of_vehicle');
            $table->string('taxation_class');
            $table->string('status_when_registered');
            $table->string('fuel_type');
            $table->string('make');
            $table->string('country_of_origin');
            $table->string('model');
            $table->text('manufactures_description');
            $table->string('wheel_base');
            $table->string('over_hang')->nullable();
            $table->string('type_of_body');
            $table->integer('year_of_manufacture');
            $table->string('colour');
            $table->text('previous_owners')->nullable();
            $table->integer('seating_capacity');
            $table->string('unladen');
            $table->string('gross')->nullable();
            $table->string('front');
            $table->string('rear');
            $table->string('dual');
            $table->string('single');
            $table->string('length_width_height');
            $table->string('provincial_council');
            $table->date('date_of_first_registration');
            $table->string('taxes_payable')->nullable();
            $table->integer('verification_score')->default(0)->comment('0 = Not Verified, 4 = Verified');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
