<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
