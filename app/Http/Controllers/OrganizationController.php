<?php

namespace App\Http\Controllers;

use App\Models\OrganizationCategory;
use App\Models\OrganizationUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    public function register($org_id)
    {
        $org_name = OrganizationCategory::findOrFail($org_id)->name;
        $title = $org_name.' Registration';
        return view('pages.organization.registration', compact('title'));
    }

    public function register_superAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:organization_users,email',
            'password' => 'required|min:8',
        ]);

        // get the referer route
        $referer = url()->previous();
        // find the organization name from the referer route
        $org_id = explode('/', $referer)[4];

        $organization_user = OrganizationUser::create([
            'u_tp_id' => 7, // Organization Super Admin
            'org_cat_id' => $org_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if($organization_user){
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            if(Auth::guard('organization_user')->attempt($credentials)){
                $request->session()->regenerate();
            }
            event(new Registered($organization_user));
        }

        return redirect()->route('verification.notice')->with('message', 'Please verify your email address.');
    }

    public function organization_login_selection()
    {
        $organization_categories = OrganizationCategory::all();
        $title = 'Organization Sign Up';
        return view('pages.organization.organization_select', compact('title', 'organization_categories'));
    }

    public function login_view()
    {
        $title = 'Organization Login';
        return view('pages.organization.login', compact('title'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check OrganizationUser Guard First
        if (Auth::guard('organization_user')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Check Default User Guard
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // If authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request, WalletController $walletController)
    {
        Auth::guard('organization_user')->logout();

        // Disconnect wallet
        $walletController->disconnect();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
