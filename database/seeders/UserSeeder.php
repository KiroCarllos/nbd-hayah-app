<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@nabdhayah.com',
            'mobile' => '0501234567',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'wallet_balance' => 0,
            'profile_image' => null,
        ]);

        // Create regular test user
        User::create([
            'name' => 'أحمد محمد',
            'email' => 'user@test.com',
            'mobile' => '0507654321',
            'password' => Hash::make('user123'),
            'is_admin' => false,
            'wallet_balance' => 1000.00,
            'profile_image' => null,
        ]);
    }
}
