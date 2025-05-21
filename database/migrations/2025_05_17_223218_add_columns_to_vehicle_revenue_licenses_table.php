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
        Schema::table('vehicle_revenue_licenses', function (Blueprint $table) {
            $table->unsignedBigInteger('ds_organization_id')->nullable()->after('valid_to');
            $table->foreign('ds_organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('ds_center_id')->nullable()->after('ds_organization_id');
            $table->foreign('ds_center_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_revenue_licenses', function (Blueprint $table) {
            $table->dropForeign(['ds_organization_id']);
            $table->dropColumn('ds_organization_id');
            $table->dropForeign(['ds_center_id']);
            $table->dropColumn('ds_center_id');
        });
    }
};
