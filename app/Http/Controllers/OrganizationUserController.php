<?php

namespace App\Http\Controllers;

use App\Mail\OrganizationUserSetPasswordMail;
use App\Models\LocationOrganization;
use App\Models\OrganizationUser;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class OrganizationUserController extends Controller
{
    public function index()
    {
        $currentUser = Auth::guard('organization_user')->user();
        Gate::forUser($currentUser)->authorize('viewAny', OrganizationUser::class);

        $organizationUsers = OrganizationUser::select('organization_users.id', 'u_tp_id', 'location_id', 'organization_users.name', 'email', 'organization_users.deleted_at')
            ->join('user_types', 'user_types.id', 'organization_users.u_tp_id')
            ->where('organization_id', $currentUser->organization_id)
            ->where('user_types.name', '!=', 'Organization Super Admin')
            ->where('organization_users.id', '!=', $currentUser->id)
            // 👇 Conditionally filter based on user type
            ->when($currentUser->userType->name === 'Organization Manager', function ($query) use ($currentUser) {
                $query->where('organization_users.location_id', $currentUser->location_id);
                $query->where('user_types.name', '!=', 'Organization Admin');
                $query->where('user_types.name', '!=', 'Organization Manager');
            })

            ->when($currentUser->userType->name === 'Organization Admin', function ($query) use ($currentUser) {
                $query->where('organization_users.location_id', $currentUser->location_id);
                $query->where('user_types.name', '!=', 'Organization Admin');
            })
            ->withTrashed()
            ->get();

        return view('pages.users.dashboard.manage_users', compact('organizationUsers'));
    }

    public function create()
    {
        $currentUser = Auth::guard('organization_user')->user();
        Gate::forUser($currentUser)->authorize('create', OrganizationUser::class);
        $addUser = true;

        $userTypes = UserType::select('id', 'name')
            ->where('name', 'like', '%Organization%')
            ->where('name', '!=', 'Organization Super Admin')
            ->when($currentUser->userType->name === 'Organization Manager', function ($query) use ($currentUser) {
                $query->where('name', '!=', 'Organization Admin');
                $query->where('name', '!=', 'Organization Manager');
            })
            ->when($currentUser->userType->name === 'Organization Admin', function ($query) use ($currentUser) {
                $query->where('name', '!=', 'Organization Admin');
            })
            ->get();

        $locations = LocationOrganization::select('l.id', 'l.name')
            ->join('locations AS l', 'location_organizations.location_id', 'l.id')
            ->where('org_id', $currentUser->organization_id)
            ->when($currentUser->userType->name === 'Organization Manager', function ($query) use ($currentUser) {
                $query->where('location_organizations.location_id', $currentUser->location_id);
            })
            ->when($currentUser->userType->name === 'Organization Admin', function ($query) use ($currentUser) {
                $query->where('location_organizations.location_id', $currentUser->location_id);
            })
            ->get();

        return view('pages.users.dashboard.add_user', compact('userTypes', 'locations', 'addUser'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::guard('organization_user')->user();
        Gate::forUser($currentUser)->authorize('create', OrganizationUser::class);

        $validated = $request->validate([
            'org_user_name' => 'required|string|max:255',
            'org_user_email' => 'required|email|unique:organization_users,email',
            'org_user_type_id' => 'required|exists:user_types,id',
            'org_user_loc_id' => 'required|exists:locations,id',
            'org_user_pass' => 'required|string|min:8',
        ]);

        $organizationUser = OrganizationUser::create([
            'u_tp_id' => $validated['org_user_type_id'],
            'organization_id' => $currentUser->organization_id,
            'location_id' => $validated['org_user_loc_id'],
            'org_cat_id' => $currentUser->org_cat_id,
            'name' => $validated['org_user_name'],
            'email' => $validated['org_user_email'],
            'password' => Hash::make($validated['org_user_pass']),
            'must_set_password' => true,
        ]);
        // send email to user to verify email and to change the password
        $link = URL::temporarySignedRoute('organizationUser.setPassword',
            now()->addDays(3),
            ['user' => $organizationUser->id]
        );
        Mail::to($organizationUser->email)->send(new OrganizationUserSetPasswordMail($currentUser, $organizationUser, $link));
        return redirect()->route('dashboard.organizationUser.index')->with('success', 'User Created Successfully.');
    }

    public function edit(OrganizationUser $organizationUser, $id)
    {
        $currentUser = Auth::guard('organization_user')->user();
        $userToUpdate = OrganizationUser::findOrFail($id);

        Gate::forUser($currentUser)->authorize('update', $userToUpdate);

        $addUser = false;

        $locations = LocationOrganization::select('l.id', 'l.name')
            ->join('locations AS l', 'location_organizations.location_id', 'l.id')
            ->where('org_id', $currentUser->organization_id)
            ->when($currentUser->userType->name === 'Organization Manager', function ($query) use ($currentUser) {
                $query->where('location_organizations.location_id', $currentUser->location_id);
            })
            ->when($currentUser->userType->name === 'Organization Admin', function ($query) use ($currentUser) {
                $query->where('location_organizations.location_id', $currentUser->location_id);
            })
            ->get();

        $userTypes = UserType::select('id', 'name')
            ->where('name', 'like', '%Organization%')
            ->where('name', '!=', 'Organization Super Admin')
            ->when($currentUser->userType->name === 'Organization Manager', function ($query) use ($currentUser) {
                $query->where('name', '!=', 'Organization Admin');
                $query->where('name', '!=', 'Organization Manager');
            })
            ->when($currentUser->userType->name === 'Organization Admin', function ($query) use ($currentUser) {
                $query->where('name', '!=', 'Organization Admin');
            })
            ->get();

        $organizationUser = OrganizationUser::select(
                [
                    'organization_users.id',
                    'organization_users.name',
                    'organization_users.u_tp_id',
                    'email',
                    'organization_users.location_id',
                    'l.name AS location_name',
                    'ut.name AS user_type_name',
                ]
            )
            ->join('locations AS l', 'organization_users.location_id', 'l.id')
            ->join('user_types AS ut', 'organization_users.u_tp_id', 'ut.id')
            ->where('organization_users.id', $id)
            ->first();

        return view('pages.users.dashboard.add_user', compact('organizationUser', 'userTypes', 'locations', 'addUser'));
    }

    public function update(Request $request, $id)
    {
        $currentUser = Auth::guard('organization_user')->user();
        $userToUpdate = OrganizationUser::findOrFail($id);

        Gate::forUser($currentUser)->authorize('update', $userToUpdate);

        $validated = $request->validate([
            'org_user_name' => 'required|string|max:255',
            'org_user_email' => 'required|email|unique:organization_users,email,' . $id,
            'org_user_type_id' => 'required|exists:user_types,id',
            'org_user_loc_id' => 'required|exists:locations,id',
            'org_user_pass' => 'nullable',
        ]);

        $userToUpdate->update(
            [
                'u_tp_id' => $validated['org_user_type_id'],
                'location_id' => $validated['org_user_loc_id'],
                'name' => $validated['org_user_name'],
                'email' => $validated['org_user_email'],
                'password' => !empty($validated['org_user_pass']) ? Hash::make($validated['org_user_pass']) : $userToUpdate->password,
            ]
        );
        return redirect()->route('dashboard.organizationUser.index')->with('success', 'User Updated Successfully.');
    }

    public function toggleOrganizationUser($id)
    {
        $organizationUser = OrganizationUser::withTrashed()->findOrFail($id);
        $currentUser = Auth::guard('organization_user')->user();

        if ($organizationUser->trashed()) {
            Gate::forUser($currentUser)->authorize('delete', $organizationUser);

            // If already deleted, restore the organizationUser
            $organizationUser->restore();
            session()->flash('success', 'Organization User Restored Successfully.');
        } else {
            Gate::forUser($currentUser)->authorize('restore', $organizationUser);
            // If not deleted, soft delete the location
            $organizationUser->delete();

            session()->flash('success', 'Organization User Deleted Successfully.');
        }

        return redirect()->back();
    }
}
