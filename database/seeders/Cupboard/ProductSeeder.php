<?php

namespace Database\Seeders\Cupboard;

use App\Models\Cupboard\Product;
use Illuminate\Database\Seeder;

// php artisan db:seed --class="Database\\Seeders\\Cupboard\\ProductSeeder"
class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::factory(20)->withReview()->create();
    }
}