<?php

namespace App\Http\Controllers;

use App\Models\StaffProfile;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function index()
    {
        $roles = Role::whereIn('name', [
            'admin',
            'teacher',
            'form_teacher',
            'accountant',
            'librarian',
            'super_admin',
        ])->get();

        $users = User::with(['roles', 'profile', 'staffProfile'])
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'teacher', 'form_teacher', 'accountant', 'librarian', 'super_admin']);
            })
            ->orderBy('name')
            ->get();

        return Inertia::render('Staff/Index', [
            'roles' => $roles,
            'staff' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'username' => ['nullable', 'string', 'max:150', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string'],
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:40'],
            'designation' => ['nullable', 'string', 'max:120'],
            'employment_date' => ['nullable', 'date'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        $user->assignRole($data['role']);

        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'other_name' => null,
            'phone' => $data['phone'] ?? null,
        ]);

        StaffProfile::create([
            'user_id' => $user->id,
            'employment_date' => $data['employment_date'] ?? null,
            'designation' => $data['designation'] ?? null,
        ]);

        return back();
    }

    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('password')]);
        return back();
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('super_admin')) {
            return back()->withErrors(['user' => 'Cannot delete a super admin.']);
        }

        $user->delete();
        return back();
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.$user->id],
            'username' => ['nullable', 'string', 'max:150', 'unique:users,username,'.$user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'string'],
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:40'],
            'designation' => ['nullable', 'string', 'max:120'],
            'employment_date' => ['nullable', 'date'],
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'] ?? null,
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);
        $user->syncRoles([$data['role']]);

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'other_name' => null,
                'phone' => $data['phone'] ?? null,
            ]
        );

        StaffProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'employment_date' => $data['employment_date'] ?? null,
                'designation' => $data['designation'] ?? null,
            ]
        );

        return back();
    }
}
