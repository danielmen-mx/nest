<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Cupboard\User;
use Illuminate\Database\Seeder;

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
            'is_admin' => true,
            'password' => bcrypt('admin')
        ]);

        User::factory()->create([
            'username' => 'danielmen-mx',
            'email' => 'danielmenc@webunderdevelopment.com',
            'is_admin' => false,
            'password' => bcrypt('dinofili')
        ]);
    }
}
