<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'phone' => '08123456789',
                'address' => 'Jl. Olahraga No. 1, Jakarta',
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Create sample customers
        $customers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@email.com',
                'password' => Hash::make('password'),
                'phone' => '08111111111',
                'address' => 'Jl. Merdeka No. 1, Jakarta',
                'role' => 'customer',
                'status' => 'active',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@email.com',
                'password' => Hash::make('password'),
                'phone' => '08222222222',
                'address' => 'Jl. Sudirman No. 2, Jakarta',
                'role' => 'customer',
                'status' => 'active',
            ],
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad@email.com',
                'password' => Hash::make('password'),
                'phone' => '08333333333',
                'address' => 'Jl. Gatot Subroto No. 3, Jakarta',
                'role' => 'customer',
                'status' => 'active',
            ],
            [
                'name' => 'Rini Handayani',
                'email' => 'rini@email.com',
                'password' => Hash::make('password'),
                'phone' => '08444444444',
                'address' => 'Jl. Diponegoro No. 4, Jakarta',
                'role' => 'customer',
                'status' => 'active',
            ],
            [
                'name' => 'Doni Pratama',
                'email' => 'doni@email.com',
                'password' => Hash::make('password'),
                'phone' => '08555555555',
                'address' => 'Jl. Ahmad Yani No. 5, Jakarta',
                'role' => 'customer',
                'status' => 'active',
            ],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }
    }
}
