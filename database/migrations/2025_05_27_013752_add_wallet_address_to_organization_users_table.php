<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('organization_users', function (Blueprint $table) {
            $table->string('wallet_address')->nullable()->after('email_verified_at');
            $table->timestamp('wallet_connected_at')->nullable()->after('wallet_address');
            $table->boolean('wallet_verified')->default(false)->after('wallet_connected_at');
            $table->timestamp('last_wallet_verification')->nullable()->after('wallet_verified');

            // Add index for faster lookups
            $table->index('wallet_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organization_users', function (Blueprint $table) {
            $table->dropIndex(['wallet_address']);
            $table->dropColumn(['wallet_address', 'wallet_connected_at', 'wallet_verified', 'last_wallet_verification']);
        });
    }
};
