<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        User::factory()->create([
            'name' => 'Assessor',
            'email' => 'assessor@gmail.com',
            'role' => 'assessor',
            'password' => bcrypt('assessor123'),
        ]);

        User::factory()->create([
            'name' => 'Participant',
            'email' => 'participant@gmail.com',
            'role' => 'participant',
            'password' => bcrypt('participant123'),
        ]);
    }
}