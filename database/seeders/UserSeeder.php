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
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@xena.com',
            'role' => 'admin',
            'password' => 'password123',
        ]);

        // Create Team Leader User
        User::create([
            'name' => 'Team Leader',
            'email' => 'teamleader@xena.com',
            'role' => 'team_leader',
            'password' => 'password123',
        ]);

        // Create Agent User
        User::create([
            'name' => 'Agent User',
            'email' => 'agent@xena.com',
            'role' => 'agent',
            'password' => 'password123',
        ]);
    }
}
