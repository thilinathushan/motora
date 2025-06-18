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
        Schema::table('faults_prediction_reports', function (Blueprint $table) {
            $table->string('pdf_status')->default('pending')->after('error_message');
            $table->string('pdf_path')->nullable()->after('pdf_status');
            $table->text('pdf_error_message')->nullable()->after('pdf_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faults_prediction_reports', function (Blueprint $table) {
            $table->dropColumn(['pdf_status', 'pdf_path', 'pdf_error_message']);
        });
    }
};
