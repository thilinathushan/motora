<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OrganizationUser extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'u_tp_id',
        'organization_id',
        'location_id',
        'org_cat_id',
        'loc_org_id',
        'name',
        'email',
        'password',
        'must_set_password',
        'phone_number',
        'wallet_address',
        'wallet_connected_at',
        'wallet_verified',
        'last_wallet_verification'
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'wallet_connected_at' => 'datetime',
        'wallet_verified' => 'boolean',
        'last_wallet_verification' => 'datetime',
    ];

    // implement the relation between OrganizationUser and UserType
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'u_tp_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function hasRole(string $roleName): bool
    {
        // We check if the userType relationship is loaded and if its name matches.
        return $this->userType && $this->userType->name === $roleName;
    }

    public function hasCategory(string $categoryName): bool
    {
        // The magic of relationships: user -> organization -> category -> name
        return $this->organization && $this->organization->category->name === $categoryName;
    }

    public function isDepartmentOfMotorTraffic()
    {
        return $this->org_cat_id == 1;
    }
    public function isDivisionalSecretariat()
    {
        return $this->org_cat_id == 2;
    }

    public function isEmissionTestCenter()
    {
        return $this->org_cat_id == 3;
    }

    public function isInsuranceCompany()
    {
        return $this->org_cat_id == 4;
    }

    public function isServiceCenter()
    {
        return $this->org_cat_id == 5;
    }

    // Add Wallet Related Methods
    public function hasWalletConnected(): bool
    {
        return !empty($this->wallet_address);
    }

    public function isWalletVerified(): bool
    {
        return $this->wallet_verified && $this->hasWalletConnected();
    }

    public function connectWallet(string $walletAddress): void
    {
        $this->update([
            'wallet_address' => strtolower($walletAddress), // Store in lowercase for consistency
            'wallet_connected_at' => now(),
            'wallet_verified' => false, // Reset verification when connecting new wallet
        ]);
    }

    public function verifyWallet(): void
    {
        $this->update([
            'wallet_verified' => true,
        ]);
    }

    public function disconnectWallet(): void
    {
        $this->update([
            'wallet_address' => null,
            'wallet_connected_at' => null,
            'wallet_verified' => false,
            'last_wallet_verification' => null,
        ]);
    }

    public function requiresWalletVerification(): bool
    {
        return $this->isDepartmentOfMotorTraffic() && $this->hasWalletConnected();
    }

}
