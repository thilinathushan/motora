<?php

namespace App\Http\Controllers;

use App\Models\OrganizationUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class OrganizationUserPasswordController extends Controller
{
    public function showSetPasswordForm(Request $request, OrganizationUser $user)
    {
        if (! $request->hasValidSignature() || ! $user->must_set_password) {
            abort(403);
        }

        return view('components.auth.organization_user.set_password', compact('user'));
    }

    public function submitPassword(Request $request, OrganizationUser $user)
    {
        if (! $request->hasValidSignature() || ! $user->must_set_password) {
            abort(403);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->must_set_password = false;

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        $user->save();

        Auth::guard('organization_user')->login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome '.$user->name.'! Your password has been set.');
    }
}
