<?php

namespace Database\Seeders;

use App\Models\PaymentCategory;
use Illuminate\Database\Seeder;

class PaymentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaults = ['Registration', 'Renewal', 'Others'];

        foreach ($defaults as $name) {
            PaymentCategory::firstOrCreate(['name' => $name]);
        }
    }
}
