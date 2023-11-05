<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         Employee::create([
            'id' => 1,
            'first_name' => 'Haris',
            'last_name' => 'Maqsood',
            'email' => 'developer.harismaqsood@gmail.com',
            'phone' => '03368800600',
            'company_id' => 1
        ]);
    }
}
