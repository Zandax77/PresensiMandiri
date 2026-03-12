<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Sekolah;
use App\Models\Libur;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * API Controller for Presensi operations.
 *
 * @package App\Http\Controllers\Api
 */
class PresensiController extends Controller
{
    /**
     * Get today's presensi status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $today = Carbon::now();
        $todayDate = $today->toDateString();

        // Get today's presensi if exists
        /** @var Presensi|null $presensiHariIni */
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->where('tanggal', $todayDate)
            ->first();

        // Get config from school settings
        /** @var Sekolah $sekolah */
        $sekolah = Sekolah::getSekolah();
        $config = $sekolah->getConfig($today);

        // Check if today is a holiday
        /** @var Libur|null $liburHariIni */
        $liburHariIni = Libur::getLiburHariIni($todayDate);
        $isLibur = $liburHariIni || $config['is_libur'];
        $liburReason = $liburHariIni ? $liburHariIni->nama : ($config['is_libur'] ? $config['alasan'] : null);

        return response()->json([
            'presensi' => $presensiHariIni,
            'config' => $config,
            'is_libur' => $isLibur,
            'libur_reason' => $liburReason,
            'sekolah' => [
                'nama' => $sekolah->nama,
                'alamat' => $sekolah->alamat,
            ],
        ]);
    }

    /**
     * Submit presensi (datang or pulang).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'tipe' => 'required|in:datang,pulang',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        /** @var User $user */
        $user = $request->user();
        $today = Carbon::now();
        $todayDate = $today->toDateString();
        $now = Carbon::now()->format('H:i:s');

        // Check if today is a holiday
        /** @var Libur|null $liburHariIni */
        $liburHariIni = Libur::getLiburHariIni($todayDate);

        // Get config to check if day is a school day
        /** @var Sekolah $sekolah */
        $sekolah = Sekolah::getSekolah();
        $config = $sekolah->getConfig($today);

        // Check if it's a holiday or non-school day
        if ($liburHariIni) {
            return response()->json([
                'message' => 'Hari ini adalah hari libur: ' . $liburHariIni->nama,
                'error' => true,
            ], 422);
        }

        if ($config['is_libur']) {
            return response()->json([
                'message' => 'Tidak ada sekolah hari ini: ' . $config['alasan'],
                'error' => true,
            ], 422);
        }

        // Check if presensi already exists for today
        /** @var Presensi|null $presensi */
        $presensi = Presensi::where('user_id', $user->id)
            ->where('tanggal', $todayDate)
            ->first();

        $tipe = $request->tipe;

        if ($tipe === 'datang') {
            if ($presensi && $presensi->jam_datang) {
                return response()->json([
                    'message' => 'Anda sudah melakukan presensi datang hari ini!',
                    'error' => true,
                ], 422);
            }

            // Check time validity
            if ($now < $config['batas_datang_mulai'] || $now > $config['batas_datang_akhir']) {
                return response()->json([
                    'message' => 'Belum/tidak lagi dalam jam presensi datang!',
                    'error' => true,
                ], 422);
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

            return response()->json([
                'message' => 'Presensi datang berhasil!',
                'presensi' => $presensi,
            ]);
        }

        if ($tipe === 'pulang') {
            if (!$presensi) {
                return response()->json([
                    'message' => 'Anda belum melakukan presensi datang!',
                    'error' => true,
                ], 422);
            }

            if ($presensi->jam_pulang) {
                return response()->json([
                    'message' => 'Anda sudah melakukan presensi pulang hari ini!',
                    'error' => true,
                ], 422);
            }

            // Check time validity
            if ($now < $config['batas_pulang_mulai'] || $now > $config['batas_pulang_akhir']) {
                return response()->json([
                    'message' => 'Belum/tidak lagi dalam jam presensi pulang!',
                    'error' => true,
                ], 422);
            }

            $presensi->update([
                'jam_pulang' => $now,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return response()->json([
                'message' => 'Presensi pulang berhasil!',
                'presensi' => $presensi,
            ]);
        }

        return response()->json([
            'message' => 'Tipe presensi tidak valid!',
            'error' => true,
        ], 422);
    }

    /**
     * Get presensi history.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function history(Request $request): JsonResponse
    {
        $request->validate([
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2020',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        /** @var User $user */
        $user = $request->user();
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;
        $limit = $request->limit ?? 30;

        $presensi = Presensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'presensi' => $presensi,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);
    }

    /**
     * Get presensi history for specific student(s) in a period.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function historySiswa(Request $request): JsonResponse
    {
        $request->validate([
            'siswa_id' => 'nullable|integer|exists:users,id',
            'kelas' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        /** @var User $user */
        $user = $request->user();
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalAkhir = $request->tanggal_akhir;
        $siswaId = $request->siswa_id;
        $kelas = $request->kelas;

        // Get list of siswa user_ids based on filters
        $siswaQuery = Siswa::query();

        // If specific siswa_id is provided, filter by that
        if ($siswaId) {
            $siswaQuery->where('user_id', $siswaId);
        }

        // If kelas is provided, filter by kelas
        if ($kelas) {
            $siswaQuery->where('kelas', $kelas);
        }

        // If user iswali_kelas, only show students in their class
        if ($user->isWaliKelas() && $user->kelas) {
            $siswaQuery->where('kelas', $user->kelas);
        }

        // If user is siswa, only show their own data
        if ($user->isSiswa()) {
            $siswaQuery->where('user_id', $user->id);
        }

        $siswas = $siswaQuery->get();
        $siswaUserIds = $siswas->pluck('user_id')->toArray();

        // If no students found, return empty data
        if (empty($siswaUserIds)) {
            return response()->json([
                'data' => [],
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_akhir' => $tanggalAkhir,
                'total_siswa' => 0,
                'message' => 'Tidak ada data siswa ditemukan',
            ]);
        }

        // Get all presensi records for the period and students
        $presensis = Presensi::whereIn('user_id', $siswaUserIds)
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
            ->orderBy('tanggal', 'desc')
            ->orderBy('user_id', 'asc')
            ->get();

        // Organize data by siswa
        $siswaData = [];
        foreach ($siswas as $siswa) {
            $siswaPresensis = $presensis->where('user_id', $siswa->user_id);

            // Calculate statistics
            $totalHadir = $siswaPresensis->where('status', 'hadir')->count();
            $totalIzin = $siswaPresensis->where('status', 'izin')->count();
            $totalSakit = $siswaPresensis->where('status', 'sakit')->count();
            $totalAlfa = $siswaPresensis->where('status', 'alfa')->count();
            $totalPresensi = $siswaPresensis->count();

            // Get unique dates in period for this student (to calculate alfa)
            $tanggalRange = \Carbon\Carbon::parse($tanggalMulai)->toPeriod($tanggalAkhir);
            $totalHari = collect($tanggalRange)->filter(function($date) {
                // Skip weekends (Saturday = 6, Sunday = 0)
                return !in_array($date->dayOfWeek, [0, 6]);
            })->count();

            // Alfa = total school days - (hadir + izin + sakit)
            $totalAlfa = max(0, $totalHari - $totalHadir - $totalIzin - $totalSakit);

            $siswaData[] = [
                'siswa' => [
                    'user_id' => $siswa->user_id,
                    'nis' => $siswa->nis,
                    'nama' => $siswa->nama,
                    'kelas' => $siswa->kelas,
                ],
                'presensi' => $siswaPresensis->values(),
                'statistik' => [
                    'hadir' => $totalHadir,
                    'izin' => $totalIzin,
                    'sakit' => $totalSakit,
                    'alfa' => $totalAlfa,
                    'total_hari' => $totalHari,
                    'persentase_hadir' => $totalHari > 0 ? round(($totalHadir / $totalHari) * 100, 1) : 0,
                ],
            ];
        }

        return response()->json([
            'data' => $siswaData,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_akhir' => $tanggalAkhir,
            'total_siswa' => count($siswaData),
        ]);
    }
}

