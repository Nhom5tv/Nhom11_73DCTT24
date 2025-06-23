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
            'name' => 'AD001',
            'email' => 'qqmaytinh2023@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        // User::create([
        //     'name' => 'Giáo viên A',
        //     'email' => 'gv@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'giaovien'
        // ]);

        // User::create([
        //     'name' => 'Sinh viên B',
        //     'email' => 'sv@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'sinhvien'
        // ]);
            }
}
