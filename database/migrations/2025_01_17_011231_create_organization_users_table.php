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
        Schema::create('organization_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('u_tp_id');
            $table->foreign('u_tp_id')->references('id')->on('user_types');
            $table->unsignedBigInteger('org_cat_id');
            $table->foreign('org_cat_id')->references('id')->on('organization_categories');
            $table->unsignedBigInteger('loc_org_id')->nullable();
            $table->foreign('loc_org_id')->references('id')->on('location_organizations');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_users');
    }
};
