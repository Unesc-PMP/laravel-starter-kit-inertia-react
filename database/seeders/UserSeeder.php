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
        User::firstOrCreate(
            [
                'email' => 'superadmin@unesc.net',
            ],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        )->assignRole('super_admin');

        User::firstOrCreate(
            [
                'email' => 'admin@unesc.net',
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        )->assignRole('admin');

        User::factory()->count(10)->create()->each(function ($user) {
            $user->assignRole('admin');
        });
    }
}
