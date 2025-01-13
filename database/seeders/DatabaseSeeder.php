<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JournalStatus;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => bcrypt('123'),
            'is_admin' => true,
        ]);
        User::factory()->create([
            'name' => 'Agus Suroso',
            'username' => 'tuagus',
            'password' => bcrypt('123'),
            'is_admin' => false,
        ]);
        JournalStatus::create([
            'kode' => 'HADIR',
            'nama' => 'Hadir',
            'warna' => 'green',
        ]);
        JournalStatus::create([
            'kode' => 'SAKIT',
            'nama' => 'Tidak hadir karena sakit',
            'warna' => 'yellow',
        ]);
        JournalStatus::create([
            'kode' => 'DINAS',
            'nama' => 'Dinas',
            'warna' => 'blue',
        ]);
    }
}
