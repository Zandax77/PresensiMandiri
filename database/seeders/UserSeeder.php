<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin (admin utama - tidak memerlukan aktivasi)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@presensi.com',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create Kesiswaan User
        User::create([
            'name' => 'Admin Kesiswaan',
            'email' => 'kesiswaan@presensi.com',
            'password' => Hash::make('kesiswaan123'),
            'role' => 'kesiswaan',
            'is_active' => true,
        ]);

        // Create BK User
        User::create([
            'name' => 'Pak BK',
            'email' => 'bk@presensi.com',
            'password' => Hash::make('bk123'),
            'role' => 'bk',
            'is_active' => true,
        ]);

        // Get available classes from siswa table
        $classes = Siswa::distinct()->pluck('kelas')->toArray();

        // Create sample Wali Kelas for each class
        $waliKelasData = [
            ['name' => 'Wali Kelas X TKJ 1', 'kelas' => 'X TKJ 1'],
            ['name' => 'Wali Kelas X RPL 1', 'kelas' => 'X RPL 1'],
            ['name' => 'Wali Kelas XI RPL 1', 'kelas' => 'XI RPL 1'],
            ['name' => 'Wali Kelas XII RPL 1', 'kelas' => 'XII RPL 1'],
        ];

        foreach ($waliKelasData as $index => $wali) {
            User::create([
                'name' => $wali['name'],
                'email' => 'wali_' . strtolower(str_replace(' ', '', $wali['kelas'])) . '@presensi.com',
                'password' => Hash::make('wali123'),
                'role' => 'wali_kelas',
                'kelas' => $wali['kelas'],
                'is_active' => true,
            ]);
        }

        // Create Test Siswa (for testing - NIS login with 12345678)
        User::create([
            'name' => 'Siswa Test',
            'email' => '12345678', // NIS as email
            'password' => Hash::make('12345678'),
            'role' => 'siswa',
            'is_active' => true,
        ]);
    }
}

