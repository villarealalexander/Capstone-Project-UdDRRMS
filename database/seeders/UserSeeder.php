<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@cdd.edu.ph',
            'password' => Hash::make('superadmin'),
            'role' => 'superadmin',
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Viewer',
            'email' => 'viewer@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);

        User::create([
            'name' => 'Encoder',
            'email' => 'encoder@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'encoder',
        ]);

        // Additional users
        User::create([
            'name' => 'Manager',
            'email' => 'manager@cdd.edu.ph',
            'password' => Hash::make('manager123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Assistant',
            'email' => 'assistant@cdd.edu.ph',
            'password' => Hash::make('assistant123'),
            'role' => 'viewer',
        ]);

        User::create([
            'name' => 'Technician',
            'email' => 'tech@cdd.edu.ph',
            'password' => Hash::make('technician123'),
            'role' => 'encoder',
        ]);

        User::create([
            'name' => 'Clerk',
            'email' => 'clerk@cdd.edu.ph',
            'password' => Hash::make('clerk123'),
            'role' => 'encoder',
        ]);

        User::create([
            'name' => 'Developer',
            'email' => 'developer@cdd.edu.ph',
            'password' => Hash::make('dev123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Consultant',
            'email' => 'consultant@cdd.edu.ph',
            'password' => Hash::make('consultant123'),
            'role' => 'viewer',
        ]);
}
}