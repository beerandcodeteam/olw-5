<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()
            ->has(Sku::factory()
                ->hasImages(5)
                ->count(3)
            )
            ->count(50)
            ->create();
    }
}
