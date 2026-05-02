<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::factory(10)->create();

        // Admin::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('password'),
        // ]);

        // Admin::factory()->create([
        //     'nama' => 'Test Admin',
        //     'email' => 'test@example.com',
        // ]);
    }
}
