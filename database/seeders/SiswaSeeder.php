<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List of realistic Indonesian student names
        $students = [
            ['nama' => 'Ahmad Fauzi', 'kelas' => 'X TKJ 1'],
            ['nama' => 'Budi Santoso', 'kelas' => 'X TKJ 1'],
            ['nama' => 'Citra Dewi', 'kelas' => 'X TKJ 1'],
            ['nama' => 'Dewi Lestari', 'kelas' => 'X RPL 1'],
            ['nama' => 'Eko Prasetyo', 'kelas' => 'X RPL 1'],
            ['nama' => 'Fitri Ayu', 'kelas' => 'X RPL 1'],
            ['nama' => 'Gita Permata', 'kelas' => 'X MM 1'],
            ['nama' => 'Hadi Wijaya', 'kelas' => 'X MM 1'],
            ['nama' => 'Indra Gunawan', 'kelas' => 'X TAV 1'],
            ['nama' => 'Joko Pramono', 'kelas' => 'X TAV 1'],
            ['nama' => 'Kartika Sari', 'kelas' => 'XI TKJ 1'],
            ['nama' => 'Lina Rosita', 'kelas' => 'XI TKJ 1'],
            ['nama' => 'Muhammad Iqbal', 'kelas' => 'XI TKJ 1'],
            ['nama' => 'Nurul Hidayah', 'kelas' => 'XI RPL 1'],
            ['nama' => 'Putri Amanda', 'kelas' => 'XI RPL 1'],
            ['nama' => 'Rendi Hermawan', 'kelas' => 'XI RPL 1'],
            ['nama' => 'Siti Nurhaliza', 'kelas' => 'XI MM 1'],
            ['nama' => 'Toni Suharto', 'kelas' => 'XI MM 1'],
            ['nama' => 'Umar Faruq', 'kelas' => 'XII TKJ 1'],
            ['nama' => 'Vina Melati', 'kelas' => 'XII TKJ 1'],
            ['nama' => 'Wulan Sari', 'kelas' => 'XII RPL 1'],
            ['nama' => 'Yusuf Abdullah', 'kelas' => 'XII RPL 1'],
            ['nama' => 'Zahra Aulia', 'kelas' => 'XII MM 1'],
            ['nama' => 'Andi Pratama', 'kelas' => 'X TKR 1'],
            ['nama' => 'Bella Septiani', 'kelas' => 'X TKR 1'],
        ];

        foreach ($students as $index => $student) {
            // Generate unique NIS (8 digits)
            $nis = str_pad((10000000 + $index), 8, '0', STR_PAD_LEFT);

            // Create user account for siswa
            $user = User::create([
                'name' => $student['nama'],
                'email' => $nis, // NIS as email for login
                'password' => Hash::make($nis), // Password = NIS
                'role' => 'siswa',
                'is_active' => true,
            ]);

            // Create siswa record
            Siswa::create([
                'nis' => $nis,
                'nama' => $student['nama'],
                'kelas' => $student['kelas'],
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('Berhasil membuat ' . count($students) . ' data siswa dengan akun user.');
    }
}

