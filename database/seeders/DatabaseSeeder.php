<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'level' => 'admin'
        ]);
        Category::create([
            'name' => 'Olahraga',
            'slug' => 'olahraga',
        ]);
        Category::create([
            'name' => 'Makanan',
            'slug' => 'makanan',
        ]);
    }
}
