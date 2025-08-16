<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Cupboard\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\Cupboard\{ PostSeeder, ProductSeeder };

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@webunderdevelopment.com',
            'first_name' => 'Daniel',
            'last_name' => 'Mendez',
            'is_admin' => true,
            'is_landlord' => true,
            'password' => bcrypt('admin')
        ]);

        User::factory()->create([
            'username' => 'test',
            'email' => 'danielmenc@webunderdevelopment.com',
            'first_name' => 'Fili Dino',
            'last_name' => 'Mendez',
            'is_admin' => false,
            'is_landlord' => false,
            'password' => bcrypt('test')
        ]);

        Artisan::call('db:seed', ['--class' => PostSeeder::class]);
        Artisan::call('db:seed', ['--class' => ProductSeeder::class]);
        Artisan::call('passport:install');
        Artisan::call('passport:keys --force');
    }
}
