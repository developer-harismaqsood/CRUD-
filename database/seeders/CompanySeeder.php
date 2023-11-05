<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //
         Company::create([
            'id' => 11,
            'name' => 'Haris',
            'email' => 'Haris@gmail.com',
            'logo' => env('APP_URL').'logo/logo.png',
            'website' => 'abc.com'
        ]);
    }
}
