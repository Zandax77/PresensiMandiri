<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Libur;
use App\Models\PengajuanIjin;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class KendaliUser extends Controller
{
    /**
     * Display a list of non-siswa users (super admin only).
     * Super admin manages: super_admin, wali_kelas, bk, kesiswaan
     */
    public function index()
    {
        // Only kesiswaan (super admin) can access user management
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Only show non-siswa users (super_admin, wali_kelas, bk, kesiswaan)
        $users = User::where('role', '!=', 'siswa')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.index', compact('users'));
    }

    /**
     * Display students in the same class (for wali kelas).
     */
    public function siswaIndex()
    {
        $user = auth()->user();

        // Only wali_kelas can access this
        if (!$user->isWaliKelas() || !$user->kelas) {
            abort(403, 'Unauthorized access. Anda harus menjadi Wali Kelas untuk mengakses halaman ini.');
        }

        // Get students in this class with their user data
        $siswaList = Siswa::where('kelas', $user->kelas)
            ->with('user')
            ->get()
            ->map(function ($siswa) {
                return [
                    'id' => $siswa->user_id,
                    'nama' => $siswa->user->name ?? 'N/A',
                    'nis' => $siswa->nis,
                    'kelas' => $siswa->kelas,
                    'is_active' => $siswa->user->is_active ?? false,
                ];
            })
            ->toArray();

        return view('wali-kelas.siswa', compact('siswaList'));
    }

    /**
     * Activate a non-siswa user account (for super admin only).
     */
    public function activate(User $user)
    {
        $authUser = auth()->user();

        // Only super admin can activate non-siswa accounts
        if (!$authUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow activating non-siswa accounts
        if ($user->role === 'siswa') {
            abort(403, 'Super admin tidak dapat mengelola akun siswa. Hubungi wali kelas yang bersangkutan.');
        }

        // Update is_active to true (1)
        $user->is_active = 1;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Akun ' . $user->name . ' berhasil diaktifkan.');
    }

    /**
     * Deactivate a non-siswa user account (for super admin only).
     */
    public function deactivate(User $user)
    {
        $authUser = auth()->user();

        // Only super admin can deactivate non-siswa accounts
        if (!$authUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow deactivating non-siswa accounts
        if ($user->role === 'siswa') {
            abort(403, 'Super admin tidak dapat mengelola akun siswa. Hubungi wali kelas yang bersangkutan.');
        }

        // Prevent deactivating self
        if ($user->id === $authUser->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        // Update is_active to false (0)
        $user->is_active = 0;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Akun ' . $user->name . ' berhasil dinonaktifkan.');
    }

    /**
     * Reset password to default (12345678) for non-siswa accounts (for super admin only).
     */
    public function resetPassword(User $user)
    {
        $authUser = auth()->user();

        // Only super admin can reset password for non-siswa accounts
        if (!$authUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow resetting password for non-siswa accounts
        if ($user->role === 'siswa') {
            abort(403, 'Super admin tidak dapat mengelola akun siswa. Hubungi wali kelas yang bersangkutan.');
        }

        // Reset password to default 12345678
        $user->update([
            'password' => Hash::make('12345678'),
        ]);

        return redirect()->route('users.index')->with('success', 'Password ' . $user->name . ' berhasil direset ke default (12345678).');
    }

    /**
     * Delete a non-siswa user account (for super admin only).
     */
    public function destroy(User $user)
    {
        $authUser = auth()->user();

        // Only super admin can delete accounts
        if (!$authUser->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow deleting non-siswa accounts
        if ($user->role === 'siswa') {
            abort(403, 'Super admin tidak dapat menghapus akun siswa. Hubungi wali kelas yang bersangkutan.');
        }

        // Prevent deleting self
        if ($user->id === $authUser->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Store name for success message
        $userName = $user->name;

        // Delete the user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun ' . $userName . ' berhasil dihapus.');
    }

    // =====================================================
    // Methods for Wali Kelas to manage siswa accounts
    // =====================================================

    /**
     * Activate a siswa account (for wali kelas only).
     */
    public function activateSiswa(User $user)
    {
        $authUser = auth()->user();

        // Only wali_kelas can activate siswa accounts
        if (!$authUser->isWaliKelas()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow activating siswa accounts
        if ($user->role !== 'siswa') {
            abort(403, 'Wali kelas hanya dapat mengelola akun siswa.');
        }

        // Check if the siswa is in this wali kelas's class
        $siswa = Siswa::where('user_id', $user->id)
            ->where('kelas', $authUser->kelas)
            ->first();

        if (!$siswa) {
            abort(403, 'Anda hanya dapat mengelola siswa di kelas Anda.');
        }

        // Update is_active to true (1)
        $user->is_active = 1;
        $user->save();

        return redirect()->route('wali-kelas.siswa')->with('success', 'Akun ' . $user->name . ' berhasil diaktifkan.');
    }

    /**
     * Deactivate a siswa account (for wali kelas only).
     */
    public function deactivateSiswa(User $user)
    {
        $authUser = auth()->user();

        // Only wali_kelas can deactivate siswa accounts
        if (!$authUser->isWaliKelas()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow deactivating siswa accounts
        if ($user->role !== 'siswa') {
            abort(403, 'Wali kelas hanya dapat mengelola akun siswa.');
        }

        // Check if the siswa is in this wali kelas's class
        $siswa = Siswa::where('user_id', $user->id)
            ->where('kelas', $authUser->kelas)
            ->first();

        if (!$siswa) {
            abort(403, 'Anda hanya dapat mengelola siswa di kelas Anda.');
        }

        // Update is_active to false (0)
        $user->is_active = 0;
        $user->save();

        return redirect()->route('wali-kelas.siswa')->with('success', 'Akun ' . $user->name . ' berhasil dinonaktifkan.');
    }

    /**
     * Reset password to default (12345678) for siswa accounts (for wali kelas only).
     */
    public function resetPasswordSiswa(User $user)
    {
        $authUser = auth()->user();

        // Only wali_kelas can reset password for siswa accounts
        if (!$authUser->isWaliKelas()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow resetting password for siswa accounts
        if ($user->role !== 'siswa') {
            abort(403, 'Wali kelas hanya dapat mengelola akun siswa.');
        }

        // Check if the siswa is in this wali kelas's class
        $siswa = Siswa::where('user_id', $user->id)
            ->where('kelas', $authUser->kelas)
            ->first();

        if (!$siswa) {
            abort(403, 'Anda hanya dapat mengelola siswa di kelas Anda.');
        }

        // Reset password to default 12345678
        $user->update([
            'password' => Hash::make('12345678'),
        ]);

        return redirect()->route('wali-kelas.siswa')->with('success', 'Password ' . $user->name . ' berhasil direset ke default (12345678).');
    }

    /**
     * Display detailed attendance for students in the same class (for wali kelas).
     */
    public function presensiIndex(Request $request)
    {
        $user = auth()->user();

        // Only wali_kelas can access this
        if (!$user->isWaliKelas() || !$user->kelas) {
            abort(403, 'Unauthorized access. Anda harus menjadi Wali Kelas untuk mengakses halaman ini.');
        }

        // Get selected date (default: today)
        $tanggal = $request->get('tanggal', Carbon::now()->toDateString());

        // Ensure $tanggal is a Carbon instance for proper date comparison
        $tanggalCarbon = $tanggal instanceof Carbon ? $tanggal : Carbon::parse($tanggal);

        // Get students in this class with their user data
        $siswaList = Siswa::where('kelas', $user->kelas)
            ->with('user')
            ->get()
            ->map(function ($siswa) use ($tanggal, $tanggalCarbon) {
                // Get attendance for this student on selected date from Presensi table
                $presensi = Presensi::where('user_id', $siswa->user_id)
                    ->where('tanggal', $tanggal)
                    ->first();

                // Check for approved izin/sakit from PengajuanIjin
                // If selected date falls within the effective date range
                $izinRequest = PengajuanIjin::where('user_id', $siswa->user_id)
                    ->where('status', 'diterima')
                    ->whereDate('tanggal_awal', '<=', $tanggalCarbon)
                    ->whereDate('tanggal_akhir', '>=', $tanggalCarbon)
                    ->first();

                // Determine status: use presensi if exists, otherwise check pengajuan
                $status = 'alfa';
                $keterangan = null;

                if ($presensi) {
                    // Use presensi status if exists
                    $status = $presensi->status;
                    $keterangan = $presensi->keterangan;
                } elseif ($izinRequest) {
                    // Use pengajuan status if no presensi record
                    $status = $izinRequest->jenis_izin; // 'izin' or 'sakit'
                    $keterangan = $izinRequest->alasan;
                }

                return [
                    'id' => $siswa->user_id,
                    'nama' => $siswa->user->name ?? 'N/A',
                    'nis' => $siswa->nis,
                    'kelas' => $siswa->kelas,
                    'status' => $status,
                    'jam_datang' => $presensi ? $presensi->jam_datang : null,
                    'jam_pulang' => $presensi ? $presensi->jam_pulang : null,
                    'keterangan' => $keterangan,
                ];
            });

        // Calculate statistics
        // Alfa should be calculated as: total - hadir - izin - sakit
        // This ensures students with approved izin are not counted as alfa
        $total = $siswaList->count();
        $hadir = $siswaList->where('status', 'hadir')->count();
        $izin = $siswaList->where('status', 'izin')->count();
        $sakit = $siswaList->where('status', 'sakit')->count();
        $alfa = max(0, $total - $hadir - $izin - $sakit);

        $stats = [
            'total' => $total,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alfa' => $alfa,
        ];

        $stats['persentase_hadir'] = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

        return view('wali-kelas.presensi', compact('siswaList', 'stats', 'tanggal'));
    }

    /**
     * Display attendance recap with date range and class selection.
     * For Super Admin: all classes available
     * For Wali Kelas: only their own class
     */
    public function rekapIndex(Request $request)
    {
        $user = auth()->user();

        // Only Super Admin and Wali Kelas can access
        if (!$user->isSuperAdmin() && !$user->isWaliKelas()) {
            abort(403, 'Unauthorized access.');
        }

        // Get filter parameters
        $tanggalMulai = $request->get('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->get('tanggal_akhir', Carbon::now()->toDateString());
        $kelasDipilih = $request->get('kelas', $user->isWaliKelas() ? $user->kelas : '');

        // Get available classes
        $availableClasses = User::getAvailableClasses();

        // Determine which classes to show
        $kelasList = $user->isWaliKelas()
            ? [$user->kelas]
            : ($kelasDipilih ? [$kelasDipilih] : $availableClasses);

        // Get date range
        $tanggalMulaiCarbon = Carbon::parse($tanggalMulai);
        $tanggalAkhirCarbon = Carbon::parse($tanggalAkhir);

        // Get all dates in range (excluding weekends and holidays)
        $dateRange = [];
        $current = $tanggalMulaiCarbon->copy();
        while ($current->lte($tanggalAkhirCarbon)) {
            // Skip weekends (Saturday = 6, Sunday = 0)
            $dayOfWeek = $current->dayOfWeek;
            if ($dayOfWeek !== 0 && $dayOfWeek !== 6) {
                // Skip holidays
                if (!Libur::isHariLibur($current->toDateString())) {
                    $dateRange[] = $current->copy();
                }
            }
            $current->addDay();
        }

        // Get students data
        $siswaQuery = Siswa::with('user')->whereIn('kelas', $kelasList);

        // Build recap data
        $rekapData = [];
        $overallStats = [
            'total_siswa' => 0,
            'total_hadir' => 0,
            'total_izin' => 0,
            'total_sakit' => 0,
            'total_alfa' => 0,
            'total_presensi' => 0,
        ];

        $siswaList = $siswaQuery->get();
        $overallStats['total_siswa'] = $siswaList->count() * count($dateRange);

        foreach ($siswaList as $siswa) {
            $siswaStats = [
                'nama' => $siswa->user->name ?? 'N/A',
                'nis' => $siswa->nis,
                'kelas' => $siswa->kelas,
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alfa' => 0,
                'total_hari' => count($dateRange),
            ];

            foreach ($dateRange as $tanggal) {
                $tanggalStr = $tanggal->toDateString();

                // Get presensi for this student on this date
                $presensi = Presensi::where('user_id', $siswa->user_id)
                    ->where('tanggal', $tanggalStr)
                    ->first();

                // Check for approved izin/sakit
                $izinRequest = PengajuanIjin::where('user_id', $siswa->user_id)
                    ->where('status', 'diterima')
                    ->whereDate('tanggal_awal', '<=', $tanggal)
                    ->whereDate('tanggal_akhir', '>=', $tanggal)
                    ->first();

                // Determine status
                $status = null;
                if ($presensi) {
                    $status = $presensi->status;
                } elseif ($izinRequest) {
                    $status = $izinRequest->jenis_izin;
                }

                // Count stats based on actual status
                switch ($status) {
                    case 'hadir':
                        $siswaStats['hadir']++;
                        $overallStats['total_hadir']++;
                        break;
                    case 'izin':
                        $siswaStats['izin']++;
                        $overallStats['total_izin']++;
                        break;
                    case 'sakit':
                        $siswaStats['sakit']++;
                        $overallStats['total_sakit']++;
                        break;
                    case 'alfa':
                        $siswaStats['alfa']++;
                        $overallStats['total_alfa']++;
                        break;
                    // If status is null (no presensi and no izin), count as alfa
                    default:
                        $siswaStats['alfa']++;
                        $overallStats['total_alfa']++;
                        break;
                }
            }

            // Calculate percentage
            $siswaStats['persentase_hadir'] = $siswaStats['total_hari'] > 0
                ? round(($siswaStats['hadir'] / $siswaStats['total_hari']) * 100, 1)
                : 0;

            $rekapData[] = $siswaStats;
        }

        // Calculate overall percentage
        $overallStats['persentase_hadir'] = $overallStats['total_siswa'] > 0
            ? round(($overallStats['total_hadir'] / $overallStats['total_siswa']) * 100, 1)
            : 0;

        return view('rekap-presensi', compact(
            'rekapData',
            'overallStats',
            'tanggalMulai',
            'tanggalAkhir',
            'kelasDipilih',
            'availableClasses',
            'dateRange'
        ));
    }

    /**
     * Print attendance recap.
     */
    public function rekapCetak(Request $request)
    {
        $user = auth()->user();

        // Only Super Admin and Wali Kelas can access
        if (!$user->isSuperAdmin() && !$user->isWaliKelas()) {
            abort(403, 'Unauthorized access.');
        }

        // Get filter parameters
        $tanggalMulai = $request->get('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->get('tanggal_akhir', Carbon::now()->toDateString());
        $kelasDipilih = $request->get('kelas', $user->isWaliKelas() ? $user->kelas : '');

        // Get available classes
        $availableClasses = User::getAvailableClasses();

        // Determine which classes to show
        $kelasList = $user->isWaliKelas()
            ? [$user->kelas]
            : ($kelasDipilih ? [$kelasDipilih] : $availableClasses);

        // Get date range
        $tanggalMulaiCarbon = Carbon::parse($tanggalMulai);
        $tanggalAkhirCarbon = Carbon::parse($tanggalAkhir);

        // Get all dates in range (excluding weekends and holidays)
        $dateRange = [];
        $current = $tanggalMulaiCarbon->copy();
        while ($current->lte($tanggalAkhirCarbon)) {
            // Skip weekends (Saturday = 6, Sunday = 0)
            $dayOfWeek = $current->dayOfWeek;
            if ($dayOfWeek !== 0 && $dayOfWeek !== 6) {
                // Skip holidays
                if (!Libur::isHariLibur($current->toDateString())) {
                    $dateRange[] = $current->copy();
                }
            }
            $current->addDay();
        }

        // Get students data
        $siswaQuery = Siswa::with('user')->whereIn('kelas', $kelasList);

        // Build recap data
        $rekapData = [];
        $overallStats = [
            'total_siswa' => 0,
            'total_hadir' => 0,
            'total_izin' => 0,
            'total_sakit' => 0,
            'total_alfa' => 0,
            'total_presensi' => 0,
        ];

        $siswaList = $siswaQuery->get();
        $overallStats['total_siswa'] = $siswaList->count() * count($dateRange);

        foreach ($siswaList as $siswa) {
            $siswaStats = [
                'nama' => $siswa->user->name ?? 'N/A',
                'nis' => $siswa->nis,
                'kelas' => $siswa->kelas,
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alfa' => 0,
                'total_hari' => count($dateRange),
            ];

            foreach ($dateRange as $tanggal) {
                $tanggalStr = $tanggal->toDateString();

                // Get presensi for this student on this date
                $presensi = Presensi::where('user_id', $siswa->user_id)
                    ->where('tanggal', $tanggalStr)
                    ->first();

                // Check for approved izin/sakit
                $izinRequest = PengajuanIjin::where('user_id', $siswa->user_id)
                    ->where('status', 'diterima')
                    ->whereDate('tanggal_awal', '<=', $tanggal)
                    ->whereDate('tanggal_akhir', '>=', $tanggal)
                    ->first();

                // Determine status
                $status = null;
                if ($presensi) {
                    $status = $presensi->status;
                } elseif ($izinRequest) {
                    $status = $izinRequest->jenis_izin;
                }

                // Count stats based on actual status
                switch ($status) {
                    case 'hadir':
                        $siswaStats['hadir']++;
                        $overallStats['total_hadir']++;
                        break;
                    case 'izin':
                        $siswaStats['izin']++;
                        $overallStats['total_izin']++;
                        break;
                    case 'sakit':
                        $siswaStats['sakit']++;
                        $overallStats['total_sakit']++;
                        break;
                    case 'alfa':
                        $siswaStats['alfa']++;
                        $overallStats['total_alfa']++;
                        break;
                    // If status is null (no presensi and no izin), count as alfa
                    default:
                        $siswaStats['alfa']++;
                        $overallStats['total_alfa']++;
                        break;
                }
            }

            // Calculate percentage
            $siswaStats['persentase_hadir'] = $siswaStats['total_hari'] > 0
                ? round(($siswaStats['hadir'] / $siswaStats['total_hari']) * 100, 1)
                : 0;

            $rekapData[] = $siswaStats;
        }

        // Calculate overall percentage
        $overallStats['persentase_hadir'] = $overallStats['total_siswa'] > 0
            ? round(($overallStats['total_hadir'] / $overallStats['total_siswa']) * 100, 1)
            : 0;

        // Get school data
        $sekolah = Sekolah::getSekolah();

        return view('rekap-presensi-cetak', compact(
            'rekapData',
            'overallStats',
            'tanggalMulai',
            'tanggalAkhir',
            'kelasDipilih',
            'availableClasses',
            'dateRange',
            'sekolah'
        ));
    }
}

