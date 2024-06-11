<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductModel;
use App\Models\Brand;

class ModelSeeder extends Seeder
{
    public function run()
    {
        $brands = Brand::all();

        // Create 10 models for each brand
        foreach ($brands as $brand) {
            for ($i = 1; $i <= 10; $i++) {
                ProductModel::create([
                    'name' => 'Model ' . $i,
                    'brand_id' => $brand->id,
                ]);
            }
        }
    }
}
