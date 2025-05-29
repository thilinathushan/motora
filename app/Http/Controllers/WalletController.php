<?php

namespace App\Http\Controllers;

use App\Models\OrganizationUser;
use Carbon\Carbon;
use Elliptic\EC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use kornrunner\Keccak;

class WalletController extends Controller
{
    // Connect wallet to the user's account
    public function connect(Request $request)
    {
        $request->validate([
            'wallet_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
        ]);

        try {
            $user = Auth::guard('organization_user')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Check if wallet is already connected to another user
            $existingUser = OrganizationUser::where('wallet_address', $request->wallet_address)
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'This wallet is already connected to another account'
                ], 409);
            }

            // Update user with wallet address
            $user->update([
                'wallet_address' => $request->wallet_address,
                'wallet_connected_at' => Carbon::now(),
                'wallet_verified' => false, // Will be verified after signature
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wallet connected successfully',
                'wallet_address' => $request->wallet_address
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect wallet'
            ], 500);
        }
    }

    // Generate verification message
    public function generateMessage()
    {
        try {
            $user = Auth::guard('organization_user')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $nonce = Str::random(32);
            $timestamp = Carbon::now()->toISOString();

            $message = "Verify your identity for " . config('app.name') . "\n\n" .
                "User: " . $user->email . "\n" .
                "Time: " . $timestamp . "\n" .
                "Nonce: " . $nonce;

            // Store message in session for verification
            session([
                'verification_message' => $message,
                'verification_nonce' => $nonce,
                'verification_timestamp' => $timestamp
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'nonce' => $nonce
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate verification message'
            ], 500);
        }
    }

    // Verify wallet signature
    public function verify(Request $request)
    {
        $request->validate([
            'signature' => 'required|string',
            'wallet_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
        ]);

        try {
            $user = Auth::guard('organization_user')->user();
            $message = session('verification_message', '');

            if (!$user || !$message || strtolower($user->wallet_address) !== strtolower($request->wallet_address)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verification failed: Invalid request or wallet mismatch.'
                ], 400);
            }

            // Verify the signature
            $isValid = $this->verifySignature(
                $message,
                $request->signature,
                $request->wallet_address
            );

            if($isValid) {
                // Mark wallet as verified
                $user->update([
                    'wallet_verified' => true,
                    'last_wallet_verification' => Carbon::now()
                ]);

                // Set session flag for crypto verification
                session([
                    'crypto_verified_for_session' => true,
                    'crypto_verified_at' => Carbon::now()->timestamp
                ]);

                // Clear the one-time-use verification message
                session()->forget('verification_message');

                return response()->json([
                    'success' => true,
                    'message' => 'Signature verified successfully',
                    'verified' => true
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid signature'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during verification.'
            ], 500);
        }
    }

    
    // Verify Ethereum signature
    private function verifySignature(string $message, string $signature, string $expectedAddress) : bool
    {
        try {
            $msglen = strlen($message);
            // Create message hash (Ethereum signed message format)
            $messageHash = $this->hashMessage($message, $msglen);
            
            $sign   = ["r" => substr($signature, 2, 64), "s" => substr($signature, 66, 64)];
            $recid  = ord(hex2bin(substr($signature, 130, 2))) - 27; 

            // Recover public key and address
            $recoveredAddress = $this->recoverAddress($messageHash, $sign, $recid);

            // Compare address (case-insensitive)
            return strtolower($recoveredAddress) === strtolower($expectedAddress);
        } catch (\Exception $e) {
            return false;
        }
    }

    // Hash message with Ethereum prefix
    private function hashMessage(string $message, int $msglen) : string
    {
        return Keccak::hash("\x19Ethereum Signed Message:\n{$msglen}{$message}", 256);
    }

    // Recover Ethereum address from signature
    private function recoverAddress(string $hash, array $sign, int $recid) : string
    {
        $ec = new EC('secp256k1');
        $publicKey = $ec->recoverPubKey($hash, $sign, $recid);

        // Convert the recovered public key to an Ethereum address
        $recoveredAddress = "0x" . substr(Keccak::hash(substr(hex2bin($publicKey->encode("hex")), 1), 256), 24);
        return $recoveredAddress;
    }

    // Disconnect wallet
    public function disconnect()
    {
        try {
            $user = Auth::guard('organization_user')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $user->disconnectWallet();

            // Clear session
            session()->forget([
                'crypto_verified_for_session',
                'crypto_verified_at',
                'temp_wallet_address'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wallet disconnected successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to disconnect wallet'
            ], 500);
        }
    }
}
