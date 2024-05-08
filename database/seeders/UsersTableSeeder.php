<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $adminData = [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('Pass@123'),
        ];

        $subadminData = [
            'name' => 'Sub Admin',
            'email' => 'subadmin@admin.com',
            'password' => Hash::make('Pass@123'),
        ];

        $admin = User::create($adminData);
        $subadmin = User::create($subadminData);

    }
}
