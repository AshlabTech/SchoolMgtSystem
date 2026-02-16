<?php

namespace Database\Seeders;

use App\Models\StaffProfile;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffRolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::query()
            ->whereIn('name', ['admin', 'teacher', 'form_teacher', 'accountant', 'librarian'])
            ->orderBy('name')
            ->get();

        foreach ($roles as $role) {
            $slug = str_replace('_', '.', $role->name);
            $user = User::firstOrCreate(
                ['email' => "{$slug}@schoolapp.test"],
                [
                    'name' => str($role->name)->replace('_', ' ')->title()->value(),
                    'username' => str($role->name)->replace('_', '')->lower()->value(),
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );

            if (!$user->hasRole($role->name)) {
                $user->assignRole($role->name);
            }

            StaffProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_code' => 'EMP'.str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
                    'designation' => str($role->name)->replace('_', ' ')->title()->value(),
                    'employment_date' => now()->subYear(),
                ]
            );

            UserProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => str($user->name)->before(' ')->value(),
                    'last_name' => str($user->name)->after(' ')->value(),
                ]
            );
        }
    }
}
