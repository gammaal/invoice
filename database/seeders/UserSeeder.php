<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@invoice.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Akun Staff
        User::create([
            'name'     => 'Staff Satu',
            'email'    => 'staff@invoice.com',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);
    }
}
