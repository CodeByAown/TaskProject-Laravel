<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        // Create 5 brands
        for ($i = 1; $i <= 5; $i++) {
            Brand::create([
                'name' => 'Brand ' . $i,
            ]);
        }
    }
}
