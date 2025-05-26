<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCryptographicVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::guard('organization_user')->check()) {
            $user = Auth::guard('organization_user')->user();

            if ($user->isDepartmentOfMotorTraffic()) {
                // Check if this is a resubmission after crypto verification
                if ($request->session()->get('crypto_verified_for_session')) {
                    // User is already verified for this session, allow request
                    return $next($request);
                }

                // Check if this is a resubmission after crypto verification
                if ($request->session()->get('crypto_verified') && $request->session()->get('resubmitting_form')) {
                    // Clear the flags and set session-wide verification
                    session()->forget(['crypto_verified', 'resubmitting_form']);
                    session(['crypto_verified_for_session' => true]);
                    return $next($request);
                }

                // If not verified for session, require verification
                if (!$request->session()->get('crypto_verified_for_session')) {
                    // Store the original request data
                    session([
                        'pending_form_action' => $request->url(),
                        'pending_form_data' => $request->all(),
                        'pending_form_method' => $request->method(),
                    ]);

                    // For AJAX requests, return JSON
                    if ($request->ajax()) {
                        return response()->json([
                            'requires_crypto_verification' => true,
                            'message' => 'Cryptographic verification required',
                        ]);
                    }

                    // For regular form submissions, redirect back with modal trigger
                    return back()->withInput()->with('show_crypto_modal', true);
                }
            }
        }
        return $next($request);
    }
}
