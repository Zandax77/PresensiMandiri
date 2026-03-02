<?php

namespace Database\Seeders;

use App\Models\Presensi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PresensiSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all siswa users
        $siswaUsers = User::where('role', 'siswa')->get();

        if ($siswaUsers->isEmpty()) {
            $this->command->warn('Tidak ada user siswa. Silakan jalankan SiswaSeeder terlebih dahulu.');
            return;
        }

        // Generate presensi for the last 30 days
        $daysToGenerate = 30;

        foreach ($siswaUsers as $user) {
            for ($day = 0; $day < $daysToGenerate; $day++) {
                // Skip some random days (like weekends - assumption: 0 = Sunday, 6 = Saturday)
                $date = now()->subDays($day);
                if ($date->dayOfWeek == 0 || $date->dayOfWeek == 6) {
                    continue;
                }

                // Randomly decide if student is present (80% hadir, 20% alfa/izin/sakit)
                $statusRoll = rand(1, 100);

                if ($statusRoll <= 70) {
                    // 70% Hadir
                    $status = 'hadir';
                    $jamDatang = sprintf('%02d:%02d:00',
                        rand(6, 7),
                        rand(0, 59)
                    );
                    $jamPulang = sprintf('%02d:%02d:00',
                        rand(14, 15),
                        rand(0, 59)
                    );
                    $keterangan = null;
                } elseif ($statusRoll <= 85) {
                    // 15% Alfa
                    $status = 'alfa';
                    $jamDatang = null;
                    $jamPulang = null;
                    $keterangan = null;
                } elseif ($statusRoll <= 93) {
                    // 8% Izin
                    $status = 'izin';
                    $jamDatang = null;
                    $jamPulang = null;
                    $keterangan = 'Izin tidak masuk sekolah';
                } else {
                    // 7% Sakit
                    $status = 'sakit';
                    $jamDatang = null;
                    $jamPulang = null;
                    $keterangan = 'Sakit flu/demam';
                }

                Presensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $date->format('Y-m-d'),
                    'jam_datang' => $jamDatang,
                    'jam_pulang' => $jamPulang,
                    'status' => $status,
                    'latitude' => -6.1751 + (rand(-100, 100) / 10000), // Jakarta area
                    'longitude' => 106.8650 + (rand(-100, 100) / 10000),
                    'keterangan' => $keterangan,
                ]);
            }
        }

        $totalPresensi = $daysToGenerate * $siswaUsers->count() * 5 / 7; // Approximate (skipping weekends)
        $this->command->info('Berhasil membuat data presensi untuk ' . $siswaUsers->count() . ' siswa.');
    }
}

