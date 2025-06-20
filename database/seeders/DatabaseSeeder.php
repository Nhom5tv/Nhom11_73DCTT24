<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Giáo viên A',
            'email' => 'gv@example.com',
            'password' => Hash::make('password'),
            'role' => 'giaovien'
        ]);

        User::create([
            'name' => 'Sinh viên B',
            'email' => 'sv@example.com',
            'password' => Hash::make('password'),
            'role' => 'sinhvien'
        ]);
            }
}
