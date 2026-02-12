<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@udaan.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Create Cameraman users
        User::create([
            'name' => 'John Cameraman',
            'email' => 'cameraman@udaan.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_CAMERAMAN,
        ]);

        User::create([
            'name' => 'Mike Camera',
            'email' => 'mike@udaan.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_CAMERAMAN,
        ]);

        // Create Editor users
        User::create([
            'name' => 'Sarah Editor',
            'email' => 'editor@udaan.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_EDITOR,
        ]);

        User::create([
            'name' => 'Emma Edit',
            'email' => 'emma@udaan.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_EDITOR,
        ]);
    }
}

