<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Brand;
use App\Models\ProductModel;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get random brands and models
        $brands = Brand::inRandomOrder()->limit(5)->get();
        $models = ProductModel::inRandomOrder()->limit(10)->get();

        // Create 25 items with random brands and models
        for ($i = 1; $i <= 25; $i++) {
            $brand = $brands->random();
            $model = $models->random();

            Item::create([
                'name' => $faker->word,
                'amount' => $faker->numberBetween(100, 1000),
                'brand_id' => $brand->id,
                'model_id' => $model->id,
            ]);
        }
    }
}
