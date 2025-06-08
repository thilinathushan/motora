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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->boolean('is_blockchain_created')->default(false)->after('certificate_url');
            $table->timestamp('blockchain_created_at')->nullable()->after('is_blockchain_created');
            $table->string('transaction_id')->nullable()->after('blockchain_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['is_blockchain_created', 'blockchain_created_at', 'transaction_id']);
        });
    }
};
