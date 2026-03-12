<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\Libur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiRecorder extends Controller
{
    /**
     * Display the presensi recorder page.
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user is wali_kelas or bk
        if (!in_array($user->role, ['wali_kelas', 'bk', 'super_admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        $today = Carbon::now()->toDateString();

        // Get students based on role
        if ($user->role === 'wali_kelas') {
            // Wali kelas can only record attendance for their own class
            // Filter by siswa.kelas to match dashboard logic
            $students = Siswa::where('kelas', $user->kelas)
                ->with(['user' => function ($q) {
                    $q->select('id', 'name', 'email', 'kelas', 'is_active');
                }])
                ->get();
        } else {
            // BK and Super Admin can record for all students
            $students = Siswa::with(['user' => function ($q) {
                $q->select('id', 'name', 'email', 'kelas', 'is_active');
            }])->get();
        }

        // Get today's presensi records
        $presensis = Presensi::whereDate('tanggal', $today)
            ->with('user', 'siswa')
            ->get()
            ->keyBy('user_id');

        // Calculate initial counts based on existing presensi records
        // A student is counted as "present" if they have jam_datang recorded
        $presentCount = $presensis->filter(function ($p) {
            return !is_null($p->jam_datang);
        })->count();
        $absentCount = $students->count() - $presentCount;

        // Check if today is a holiday
        $isLibur = Libur::getLiburHariIni($today) || Sekolah::getSekolah()->isLiburDay(Carbon::now());
        $liburReason = Libur::getLiburHariIni($today)?->nama ?? 'Hari libur';

        return view('presensi.recorder', compact('students', 'presensis', 'today', 'isLibur', 'liburReason', 'presentCount', 'absentCount'));
    }

    /**
     * Search student by NIS or name.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1',
        ]);

        $user = Auth::user();
        $query = $request->input('q');

        // Get students based on role
        $query_builder = Siswa::with(['user' => function ($q) {
            $q->select('id', 'name', 'email', 'kelas', 'is_active');
        }]);

        if ($user->role === 'wali_kelas') {
            // Wali kelas can only search in their own class
            $query_builder->where('kelas', Auth::user()->kelas);
        }

        // Search by NIS or name (wrapped in group to preserve class filter)
        $results = $query_builder
            ->where(function ($q) use ($query) {
                $q->where('nis', 'LIKE', "%{$query}%")
                  ->orWhere('nama', 'LIKE', "%{$query}%");
            })
            ->get();

        return response()->json($results);
    }

    /**
     * Record presensi for a student.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tipe' => 'required|in:datang,pulang',
        ]);

        $user = Auth::user();
        $siswa = Siswa::findOrFail($request->siswa_id);
        $student_user = $siswa->user;

        // Check authorization
        if ($user->role === 'wali_kelas' && $student_user->kelas !== $user->kelas) {
            return response()->json(['success' => false, 'message' => 'Anda hanya bisa mencatat presensi siswa kelas Anda.'], 403);
        }

        $today = Carbon::now()->toDateString();
        $now = Carbon::now()->format('H:i:s');

        // Check if today is a holiday
        $liburHariIni = Libur::getLiburHariIni($today);
        $sekolah = Sekolah::getSekolah();
        $config = $sekolah->getConfig(Carbon::now());

        if ($liburHariIni) {
            return response()->json(['success' => false, 'message' => 'Hari ini adalah hari libur: ' . $liburHariIni->nama], 400);
        }

        if ($config['is_libur']) {
            return response()->json(['success' => false, 'message' => 'Tidak ada sekolah hari ini'], 400);
        }

        // Get or create presensi
        $presensi = Presensi::where('user_id', $student_user->id)
            ->where('tanggal', $today)
            ->first();

        $tipe = $request->tipe;

        if ($tipe === 'datang') {
            if ($presensi && $presensi->jam_datang) {
                return response()->json(['success' => false, 'message' => 'Siswa ini sudah dicatat presensi datang hari ini'], 400);
            }

            // Check time validity
            if ($now < $config['batas_datang_mulai'] || $now > $config['batas_datang_akhir']) {
                // Still record but with warning
                $message = 'Presensi datang berhasil dicatat (di luar jam presensi)';
            } else {
                $message = 'Presensi datang berhasil dicatat';
            }

            if (!$presensi) {
                $presensi = Presensi::create([
                    'user_id' => $student_user->id,
                    'tanggal' => $today,
                    'jam_datang' => $now,
                    'status' => 'hadir',
                ]);
            } else {
                $presensi->update([
                    'jam_datang' => $now,
                    'status' => 'hadir',
                ]);
            }
        } elseif ($tipe === 'pulang') {
            if (!$presensi || !$presensi->jam_datang) {
                return response()->json(['success' => false, 'message' => 'Siswa belum dicatat presensi datang'], 400);
            }

            if ($presensi->jam_pulang) {
                return response()->json(['success' => false, 'message' => 'Siswa ini sudah dicatat presensi pulang hari ini'], 400);
            }

            // Check time validity
            if ($now < $config['batas_pulang_mulai'] || $now > $config['batas_pulang_akhir']) {
                $message = 'Presensi pulang berhasil dicatat (di luar jam presensi)';
            } else {
                $message = 'Presensi pulang berhasil dicatat';
            }

            $presensi->update([
                'jam_pulang' => $now,
                'status' => 'hadir',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $presensi,
        ]);
    }

    /**
     * Reset presensi for a student (admin only).
     */
    public function reset(Request $request)
    {
        $request->validate([
            'presensi_id' => 'required|exists:presensis,id',
            'tipe' => 'required|in:datang,pulang',
        ]);

        $user = Auth::user();

        // Only super admin can reset
        if ($user->role !== 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk reset presensi'], 403);
        }

        $presensi = Presensi::findOrFail($request->presensi_id);

        if ($request->tipe === 'datang') {
            $presensi->update(['jam_datang' => null]);
            $message = 'Presensi datang direset';
        } else {
            $presensi->update(['jam_pulang' => null]);
            $message = 'Presensi pulang direset';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Get presensi data for displaying in the recorder.
     */
    public function getPresensi(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $user = Auth::user();
        $tanggal = $request->input('tanggal');

        $query = Presensi::whereDate('tanggal', $tanggal)
            ->with(['user', 'siswa']);

        if ($user->role === 'wali_kelas') {
            // Filter by siswa.kelas to match the student list filter
            $query->whereHas('siswa', function ($q) {
                $q->where('kelas', Auth::user()->kelas);
            });
        }

        $presensis = $query->get();

        return response()->json([
            'success' => true,
            'data' => $presensis,
        ]);
    }
}
