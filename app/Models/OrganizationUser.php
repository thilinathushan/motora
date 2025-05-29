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
        'org_cat_id',
        'loc_org_id',
        'name',
        'email',
        'password',
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

    public function isDepartmentOfMotorTraffic()
    {
        return $this->org_cat_id == 5;
    }
    public function isDivisionalSecretariat()
    {
        return $this->org_cat_id == 6;
    }

    public function isEmissionTestCenter()
    {
        return $this->org_cat_id == 7;
    }

    public function isInsuranceCompany()
    {
        return $this->org_cat_id == 8;
    }

    public function isServiceCenter()
    {
        return $this->org_cat_id == 9;
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
