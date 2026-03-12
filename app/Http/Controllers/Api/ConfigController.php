<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\Libur;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConfigController extends Controller
{
    /**
     * Get school configuration including location and attendance times.
     */
    public function index(Request $request)
    {
        $sekolah = Sekolah::getSekolah();
        $today = Carbon::now();

        // Get today's config
        $config = $sekolah->getConfig($today);

        // Get today's holiday
        $liburHariIni = Libur::getLiburHariIni($today->toDateString());

        // Get jam presensi for all days
        $jamPresensi = $sekolah->jam_presensi ?? Sekolah::defaultJamPresensi();

        return response()->json([
            'sekolah' => [
                'nama' => $sekolah->nama,
                'alamat' => $sekolah->alamat,
                'telepon' => $sekolah->telepon,
                'email' => $sekolah->email,
                'logo_url' => $sekolah->logo_url,
            ],
            'lokasi' => [
                'latitude' => $sekolah->latitude,
                'longitude' => $sekolah->longitude,
                'radius_ijin' => $config['radius_ijin'] ?? 100,
            ],
            'jam_presensi' => $jamPresensi,
            'config_hari_ini' => [
                'is_libur' => $config['is_libur'],
                'alasan' => $liburHariIni ? $liburHariIni->nama : ($config['is_libur'] ? $config['alasan'] : null),
                'batas_datang_mulai' => $config['batas_datang_mulai'],
                'batas_datang_akhir' => $config['batas_datang_akhir'],
                'batas_pulang_mulai' => $config['batas_pulang_mulai'],
                'batas_pulang_akhir' => $config['batas_pulang_akhir'],
            ],
            'libur' => [
                'hari_ini' => $liburHariIni ? [
                    'nama' => $liburHariIni->nama,
                    'jenis' => $liburHariIni->jenis,
                    'tanggal_mulai' => $liburHariIni->tanggal_mulai,
                    'tanggal_akhir' => $liburHariIni->tanggal_akhir,
                ] : null,
            ],
        ]);
    }

    /**
     * Get attendance times for specific date.
     */
    public function jamPresensi(Request $request)
    {
        $request->validate([
            'tanggal' => 'nullable|date',
        ]);

        $sekolah = Sekolah::getSekolah();
        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal) : Carbon::now();

        // Check if it's a holiday
        $libur = Libur::getLiburHariIni($tanggal->toDateString());

        // Get config for the date
        $config = $sekolah->getConfig($tanggal);

        // Get day name in Indonesian
        $dayName = strtolower($tanggal->format('l'));
        $dayMap = [
            'monday' => 'senin',
            'tuesday' => 'selasa',
            'wednesday' => 'rabu',
            'thursday' => 'kamis',
            'friday' => 'jumat',
            'saturday' => 'sabtu',
            'sunday' => 'minggu',
        ];
        $hari = $dayMap[$dayName] ?? $dayName;

        return response()->json([
            'tanggal' => $tanggal->toDateString(),
            'hari' => $hari,
            'is_libur' => $config['is_libur'] || $libur !== null,
            'alasan_libur' => $libur ? $libur->nama : ($config['is_libur'] ? $config['alasan'] : null),
            'jam_presensi' => $libur ? null : [
                'datang' => [
                    'mulai' => $config['batas_datang_mulai'],
                    'akhir' => $config['batas_datang_akhir'],
                ],
                'pulang' => [
                    'mulai' => $config['batas_pulang_mulai'],
                    'akhir' => $config['batas_pulang_akhir'],
                ],
            ],
        ]);
    }

    /**
     * Get school location for attendance validation.
     */
    public function lokasi(Request $request)
    {
        $sekolah = Sekolah::getSekolah();

        return response()->json([
            'latitude' => $sekolah->latitude,
            'longitude' => $sekolah->longitude,
            'radius_ijin' => 100, // Default radius in meters
            'nama_sekolah' => $sekolah->nama,
        ]);
    }
}

