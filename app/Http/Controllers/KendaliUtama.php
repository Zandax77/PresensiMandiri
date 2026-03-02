<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\PengajuanIjin;
use App\Models\User;

class KendaliUtama extends Controller
{
    /**
     * Get izin/sakit counts from approved PengajuanIjin for a specific date.
     * This counts izin requests where the selected date falls within the effective date range.
     */
    private function getIzinSakitFromPengajuan($siswaIds, $tanggal)
    {
        // Ensure $tanggal is a Carbon instance for proper date comparison
        if (!$tanggal instanceof Carbon) {
            $tanggal = Carbon::parse($tanggal);
        }

        // Get approved izin requests where the selected date falls within the date range
        $izinRequests = PengajuanIjin::whereIn('user_id', $siswaIds)
            ->where('status', 'diterima')
            ->whereDate('tanggal_awal', '<=', $tanggal)
            ->whereDate('tanggal_akhir', '>=', $tanggal)
            ->get();

        $izin = $izinRequests->where('jenis_izin', 'izin')->count();
        $sakit = $izinRequests->where('jenis_izin', 'sakit')->count();

        return ['izin' => $izin, 'sakit' => $sakit];
    }

    /**
     * Display the dashboard page.
     * Only accessible by non-siswa users (admin, wali kelas, BK, kesiswaan).
     */
    public function index(Request $request)
    {
        // Get current user
        $user = auth()->user();

        // Block siswa from accessing dashboard - redirect to presensi
        if ($user->isSiswa()) {
            return redirect()->route('presensi.index')->with('error', 'Anda tidak memiliki akses ke halaman dashboard.');
        }

        // Get selected date (default: today)
        $tanggal = $request->get('tanggal', Carbon::now()->toDateString());

        // Get all unique classes
        $kelasList = Siswa::distinct()->pluck('kelas')->filter()->sort()->values();

        // Build statistics per class
        $statsPerKelas = [];

        // If user is Wali Kelas, only show their class
        if ($user->isWaliKelas() && $user->kelas) {
            $kelas = $user->kelas;

            // Get students in this class
            $siswaIds = Siswa::where('kelas', $kelas)->pluck('user_id');
            $totalSiswa = $siswaIds->count();

            // Get attendance for this class on selected date (from Presensi table)
            $presensi = Presensi::whereIn('user_id', $siswaIds)
                ->where('tanggal', $tanggal)
                ->get();

            $hadir = $presensi->where('status', 'hadir')->count();
            $izinPresensi = $presensi->where('status', 'izin')->count();
            $sakitPresensi = $presensi->where('status', 'sakit')->count();

            // Get izin/sakit from approved PengajuanIjin (where selected date is within effective range)
            $izinPengajuan = $this->getIzinSakitFromPengajuan($siswaIds, $tanggal);

            // Combine: use Presensi if exists, otherwise use PengajuanIjin
            $izin = $izinPresensi > 0 ? $izinPresensi : $izinPengajuan['izin'];
            $sakit = $sakitPresensi > 0 ? $sakitPresensi : $izinPengajuan['sakit'];

            // If no data at all, use pengajuan counts
            if ($izinPresensi == 0 && $sakitPresensi == 0) {
                $izin = $izinPengajuan['izin'];
                $sakit = $izinPengajuan['sakit'];
            }

            // Calculate alfa as: total_siswa - hadir - izin - sakit
            // This ensures students with approved izin are not counted as alfa
            $alfa = max(0, $totalSiswa - $hadir - $izin - $sakit);

            $statsPerKelas[] = [
                'kelas' => $kelas,
                'total_siswa' => $totalSiswa,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alfa' => $alfa,
                'persentase_hadir' => $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 1) : 0,
            ];

            // For Wali Kelas, show only their class in kelasList
            $kelasList = collect([$kelas]);
        } else {
            // Show all classes for admin/other roles
            foreach ($kelasList as $kelas) {
                // Get students in this class
                $siswaIds = Siswa::where('kelas', $kelas)->pluck('user_id');
                $totalSiswa = $siswaIds->count();

                // Get attendance for this class on selected date (from Presensi table)
                $presensi = Presensi::whereIn('user_id', $siswaIds)
                    ->where('tanggal', $tanggal)
                    ->get();

                $hadir = $presensi->where('status', 'hadir')->count();
                $izinPresensi = $presensi->where('status', 'izin')->count();
                $sakitPresensi = $presensi->where('status', 'sakit')->count();

                // Get izin/sakit from approved PengajuanIjin (where selected date is within effective range)
                $izinPengajuan = $this->getIzinSakitFromPengajuan($siswaIds, $tanggal);

                // Combine: use Presensi if exists, otherwise use PengajuanIjin
                $izin = $izinPresensi > 0 ? $izinPresensi : $izinPengajuan['izin'];
                $sakit = $sakitPresensi > 0 ? $sakitPresensi : $izinPengajuan['sakit'];

                // If no data at all, use pengajuan counts
                if ($izinPresensi == 0 && $sakitPresensi == 0) {
                    $izin = $izinPengajuan['izin'];
                    $sakit = $izinPengajuan['sakit'];
                }

                // Calculate alfa as: total_siswa - hadir - izin - sakit
                // This ensures students with approved izin are not counted as alfa
                $alfa = max(0, $totalSiswa - $hadir - $izin - $sakit);

                $statsPerKelas[] = [
                    'kelas' => $kelas,
                    'total_siswa' => $totalSiswa,
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alfa' => $alfa,
                    'persentase_hadir' => $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 1) : 0,
                ];
            }
        }

        // Calculate totals
        $totalSemuaSiswa = array_sum(array_column($statsPerKelas, 'total_siswa'));
        $totalHadir = array_sum(array_column($statsPerKelas, 'hadir'));
        $totalIzin = array_sum(array_column($statsPerKelas, 'izin'));
        $totalSakit = array_sum(array_column($statsPerKelas, 'sakit'));
        $totalAlfa = array_sum(array_column($statsPerKelas, 'alfa'));

        // Calculate overall percentage
        $persentaseHadirGlobal = $totalSemuaSiswa > 0 ? round(($totalHadir / $totalSemuaSiswa) * 100, 1) : 0;

        return view('dashboard', compact(
            'statsPerKelas',
            'tanggal',
            'kelasList',
            'totalSemuaSiswa',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlfa',
            'persentaseHadirGlobal'
        ));
    }

    /**
     * Get dashboard data as JSON for real-time updates.
     */
    public function apiData(Request $request)
    {
        // Get current user
        $user = auth()->user();

        // Block siswa from accessing dashboard API
        if ($user->isSiswa()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get selected date (default: today)
        $tanggal = $request->get('tanggal', Carbon::now()->toDateString());

        // Get all unique classes
        $kelasList = Siswa::distinct()->pluck('kelas')->filter()->sort()->values();

        // Build statistics per class
        $statsPerKelas = [];

        // If user is Wali Kelas, only show their class
        if ($user->isWaliKelas() && $user->kelas) {
            $kelas = $user->kelas;

            // Get students in this class
            $siswaIds = Siswa::where('kelas', $kelas)->pluck('user_id');
            $totalSiswa = $siswaIds->count();

            // Get attendance for this class on selected date (from Presensi table)
            $presensi = Presensi::whereIn('user_id', $siswaIds)
                ->where('tanggal', $tanggal)
                ->get();

            $hadir = $presensi->where('status', 'hadir')->count();
            $izinPresensi = $presensi->where('status', 'izin')->count();
            $sakitPresensi = $presensi->where('status', 'sakit')->count();

            // Get izin/sakit from approved PengajuanIjin (where selected date is within effective range)
            $izinPengajuan = $this->getIzinSakitFromPengajuan($siswaIds, $tanggal);

            // Combine: use Presensi if exists, otherwise use PengajuanIjin
            $izin = $izinPresensi > 0 ? $izinPresensi : $izinPengajuan['izin'];
            $sakit = $sakitPresensi > 0 ? $sakitPresensi : $izinPengajuan['sakit'];

            // If no data at all, use pengajuan counts
            if ($izinPresensi == 0 && $sakitPresensi == 0) {
                $izin = $izinPengajuan['izin'];
                $sakit = $izinPengajuan['sakit'];
            }

            // Calculate alfa as: total_siswa - hadir - izin - sakit
            // This ensures students with approved izin are not counted as alfa
            $alfa = max(0, $totalSiswa - $hadir - $izin - $sakit);

            $statsPerKelas[] = [
                'kelas' => $kelas,
                'total_siswa' => $totalSiswa,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alfa' => $alfa,
                'persentase_hadir' => $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 1) : 0,
            ];

            // For Wali Kelas, show only their class in kelasList
            $kelasList = collect([$kelas]);
        } else {
            // Show all classes for admin/other roles
            foreach ($kelasList as $kelas) {
                // Get students in this class
                $siswaIds = Siswa::where('kelas', $kelas)->pluck('user_id');
                $totalSiswa = $siswaIds->count();

                // Get attendance for this class on selected date (from Presensi table)
                $presensi = Presensi::whereIn('user_id', $siswaIds)
                    ->where('tanggal', $tanggal)
                    ->get();

                $hadir = $presensi->where('status', 'hadir')->count();
                $izinPresensi = $presensi->where('status', 'izin')->count();
                $sakitPresensi = $presensi->where('status', 'sakit')->count();

                // Get izin/sakit from approved PengajuanIjin (where selected date is within effective range)
                $izinPengajuan = $this->getIzinSakitFromPengajuan($siswaIds, $tanggal);

                // Combine: use Presensi if exists, otherwise use PengajuanIjin
                $izin = $izinPresensi > 0 ? $izinPresensi : $izinPengajuan['izin'];
                $sakit = $sakitPresensi > 0 ? $sakitPresensi : $izinPengajuan['sakit'];

                // If no data at all, use pengajuan counts
                if ($izinPresensi == 0 && $sakitPresensi == 0) {
                    $izin = $izinPengajuan['izin'];
                    $sakit = $izinPengajuan['sakit'];
                }

                // Calculate alfa as: total_siswa - hadir - izin - sakit
                // This ensures students with approved izin are not counted as alfa
                $alfa = max(0, $totalSiswa - $hadir - $izin - $sakit);

                $statsPerKelas[] = [
                    'kelas' => $kelas,
                    'total_siswa' => $totalSiswa,
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alfa' => $alfa,
                    'persentase_hadir' => $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 1) : 0,
                ];
            }
        }

        // Calculate totals
        $totalSemuaSiswa = array_sum(array_column($statsPerKelas, 'total_siswa'));
        $totalHadir = array_sum(array_column($statsPerKelas, 'hadir'));
        $totalIzin = array_sum(array_column($statsPerKelas, 'izin'));
        $totalSakit = array_sum(array_column($statsPerKelas, 'sakit'));
        $totalAlfa = array_sum(array_column($statsPerKelas, 'alfa'));

        // Calculate overall percentage
        $persentaseHadirGlobal = $totalSemuaSiswa > 0 ? round(($totalHadir / $totalSemuaSiswa) * 100, 1) : 0;

        return response()->json([
            'statsPerKelas' => $statsPerKelas,
            'tanggal' => $tanggal,
            'totalSemuaSiswa' => $totalSemuaSiswa,
            'totalHadir' => $totalHadir,
            'totalIzin' => $totalIzin,
            'totalSakit' => $totalSakit,
            'totalAlfa' => $totalAlfa,
            'persentaseHadirGlobal' => $persentaseHadirGlobal,
            'lastUpdated' => now()->format('H:i:s')
        ]);
    }
}

