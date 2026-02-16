<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage.users',
            'manage.roles',
            'manage.settings',
            'manage.classes',
            'manage.sections',
            'manage.subjects',
            'manage.exams',
            'manage.grades',
            'manage.marks',
            'manage.promotions',
            'manage.students',
            'view.students',
            'manage.payments',
            'view.payments',
            'manage.receipts',
            'manage.timetables',
            'view.timetables',
            'manage.library',
            'view.library',
            'manage.dorms',
            'manage.pins',
            'view.results',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roleMap = [
            'super_admin' => $permissions,
            'admin' => [
                'manage.users',
                'manage.settings',
                'manage.classes',
                'manage.sections',
                'manage.subjects',
                'manage.exams',
                'manage.grades',
                'manage.marks',
                'manage.promotions',
                'manage.students',
                'view.students',
                'manage.payments',
                'manage.receipts',
                'manage.timetables',
                'manage.library',
                'manage.dorms',
                'manage.pins',
            ],
            'teacher' => [
                'view.students',
                'manage.marks',
                'view.timetables',
            ],
            'form_teacher' => [
                'view.students',
                'manage.marks',
                'view.timetables',
                'view.results',
                'manage.promotions',
            ],
            'accountant' => [
                'manage.payments',
                'manage.receipts',
                'view.students',
            ],
            'student' => [
                'view.payments',
                'view.timetables',
                'view.library',
                'view.results',
            ],
            'parent' => [
                'view.students',
                'view.payments',
                'view.timetables',
                'view.results',
            ],
            'librarian' => [
                'manage.library',
            ],
        ];

        foreach ($roleMap as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
