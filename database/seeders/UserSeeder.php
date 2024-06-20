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
            'name' => 'MIS office',
            'email' => 'misoffice@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);
        User::create([
            'name' => 'Head Registrar',
            'email' => 'headregistrar@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Registrar Staff',
            'email' => 'registrar1@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);

        User::create([
            'name' => 'Registrar Staff',
            'email' => 'registrar2@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);

        User::create([
            'name' => 'Registrar Staff',
            'email' => 'registrar3@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);

        User::create([
            'name' => 'Registrar Staff',
            'email' => 'registrar4@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);

        User::create([
            'name' => 'Registrar Staff',
            'email' => 'registrar5@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);

        User::create([
            'name' => 'Archival Office',
            'email' => 'archival@cdd.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'encoder',
        ]);

}
}