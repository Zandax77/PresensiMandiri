<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Sekolah;
use App\Models\Libur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KendaliPresensi extends Controller
{
    /**
     * Display the presensi page.
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::now();
        $todayDate = $today->toDateString();

        // Get today's presensi if exists
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->where('tanggal', $todayDate)
            ->first();

        // Get config from school settings based on today's day
        $sekolah = Sekolah::getSekolah();
        $config = $sekolah->getConfig($today);

        // Check if today is a holiday
        $liburHariIni = Libur::getLiburHariIni($todayDate);

        // Combine holiday status with day-off (minggu)
        $isLibur = $liburHariIni || $config['is_libur'];
        $liburReason = $liburHariIni ? $liburHariIni->nama : ($config['is_libur'] ? $config['alasan'] : null);

        return view('presensi', compact('presensiHariIni', 'config', 'liburHariIni', 'isLibur', 'liburReason', 'sekolah'));
    }

    /**
     * Store presensi (datang or pulang).
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:datang,pulang',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $user = Auth::user();
        $today = Carbon::now();
        $todayDate = $today->toDateString();
        $now = Carbon::now()->format('H:i:s');

        // Check if today is a holiday
        $liburHariIni = Libur::getLiburHariIni($todayDate);

        // Get config to check if day is a school day
        $sekolah = Sekolah::getSekolah();
        $config = $sekolah->getConfig($today);

        // Check if it's a holiday or non-school day
        if ($liburHariIni) {
            return redirect()->back()->with('error', 'Hari ini adalah hari libur: ' . $liburHariIni->nama);
        }

        if ($config['is_libur']) {
            return redirect()->back()->with('error', 'Tidak ada sekolah hari ini: ' . $config['alasan']);
        }

        // Check if presensi already exists for today
        $presensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $todayDate)
            ->first();

        $tipe = $request->tipe;

        if ($tipe === 'datang') {
            if ($presensi && $presensi->jam_datang) {
                return redirect()->back()->with('error', 'Anda sudah melakukan presensi datang hari ini!');
            }

            // Check time validity
            if ($now < $config['batas_datang_mulai'] || $now > $config['batas_datang_akhir']) {
                return redirect()->back()->with('error', 'Belum/tidak lagi dalam jam presensi datang!');
            }

            // Create or update presensi
            if (!$presensi) {
                $presensi = Presensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $todayDate,
                    'jam_datang' => $now,
                    'status' => 'hadir',
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            } else {
                $presensi->update([
                    'jam_datang' => $now,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }

            return redirect()->back()->with('success', 'Presensi datang berhasil!');
        }

        if ($tipe === 'pulang') {
            if (!$presensi) {
                return redirect()->back()->with('error', 'Anda belum melakukan presensi datang!');
            }

            if ($presensi->jam_pulang) {
                return redirect()->back()->with('error', 'Anda sudah melakukan presensi pulang hari ini!');
            }

            // Check time validity
            if ($now < $config['batas_pulang_mulai'] || $now > $config['batas_pulang_akhir']) {
                return redirect()->back()->with('error', 'Belum/tidak lagi dalam jam presensi pulang!');
            }

            $presensi->update([
                'jam_pulang' => $now,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return redirect()->back()->with('success', 'Presensi pulang berhasil!');
        }

        return redirect()->back()->with('error', 'Tipe presensi tidak valid!');
    }
}

