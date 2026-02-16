<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
            AcademicYearSeeder::class,
            TermSeeder::class,
            SchoolClassesSeeder::class,
            SectionSeeder::class,
            PaymentCategorySeeder::class,
            SettingsSeeder::class,
            StaffRolesSeeder::class,
            CoreModelsSeeder::class,
            ResultCommentsSeeder::class,
            StudentSeeder::class,
            TimetableSeeder::class,
        ]);
    }
}
