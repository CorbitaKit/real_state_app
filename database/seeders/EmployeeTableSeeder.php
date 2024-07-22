<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'email' => 'admin@admin.com',
            'password' => 'admin12345',
            'role_id' => 1,
            'email_verified_at' => '2024-07-22 08:07:17'
        ]);

        Employee::create([
            'first_name' => 'Kit',
            'last_name' => 'Corbita',
            'address' => 'Panabo',
            'phone_number' => '09511046579',
            'user_id' => $user->id,
            'birth_day' => '1992-01-18'
        ]);
    }
}
