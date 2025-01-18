<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // User::create([
        //     'name' => 'organizer',
        //     'email' => 'organizer@gmail.com',
        //     'password' => bcrypt('organizer123'),
        //     'role' => 'organizer',
        // ]);

        // User::create([
        //     'name' => 'attende',
        //     'email' => 'attende@gmail.com',
        //     'password' => bcrypt('attende123'),
        //     'role' => 'attende',
        // ]);
    }
}
