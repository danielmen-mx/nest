<?php

namespace Database\Seeders\Cupboard;

use App\Models\Cupboard\Post;
use Illuminate\Database\Seeder;

// php artisan db:seed --class="Database\\Seeders\\Cupboard\\PostSeeder"
class PostSeeder extends Seeder
{
    public function run()
    {
        Post::factory(10)->withReview()->create();
    }
}