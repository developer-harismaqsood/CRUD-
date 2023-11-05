<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Root User (Agent)
        User::create([
            'id' => 1,
            'name' => 'Haris',
            'email' => 'developer.harismaqsood@gmail.com',
            'password' => Hash::make('12345678')
        ]);
    }
}
