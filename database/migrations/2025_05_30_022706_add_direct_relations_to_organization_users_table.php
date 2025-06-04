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
        Schema::table('organization_users', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->after('u_tp_id')->constrained('organizations');
            $table->foreignId('location_id')->nullable()->after('organization_id')->constrained('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organization_users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['location_id']);
            $table->dropColumn(['organization_id', 'location_id']);
        });
    }
};
