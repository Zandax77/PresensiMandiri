<?php

namespace App\Http\Controllers;

use App\Models\PengajuanIjin;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KendaliIzin extends Controller
{
    /**
     * Display the izin requests page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'menunggu');

        // Build query based on user role
        $query = PengajuanIjin::with(['user', 'approver']);

        if ($user->isWaliKelas() && $user->kelas) {
            // Get siswa IDs in the same class
            $siswaIds = Siswa::where('kelas', $user->kelas)->pluck('user_id');
            $query->whereIn('user_id', $siswaIds);
        }

        // Filter by status
        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $pengajuanIjin = $query->orderBy('created_at', 'desc')->get();

        // Count statistics
        $stats = [
            'menunggu' => $this->getCountByStatus('menunggu', $user),
            'diterima' => $this->getCountByStatus('diterima', $user),
            'ditolak' => $this->getCountByStatus('ditolak', $user),
        ];

        return view('izin.index', compact('pengajuanIjin', 'status', 'stats'));
    }

    /**
     * Get count of izin by status.
     */
    private function getCountByStatus($status, $user)
    {
        $query = PengajuanIjin::where('status', $status);

        if ($user->isWaliKelas() && $user->kelas) {
            $siswaIds = Siswa::where('kelas', $user->kelas)->pluck('user_id');
            $query->whereIn('user_id', $siswaIds);
        }

        return $query->count();
    }

    /**
     * Display the student's own izin requests.
     */
    public function saya(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'semua');

        $query = PengajuanIjin::where('user_id', $user->id);

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $pengajuanIjin = $query->orderBy('created_at', 'desc')->get();

        return view('izin.saya', compact('pengajuanIjin', 'status'));
    }

    /**
     * Store a new izin request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_izin' => 'required|in:izin,sakit',
            'tanggal_awal' => 'required|date|before_or_equal:tanggal_akhir',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'alasan' => 'required|string|min:10',
            'berkas' => 'nullable|file|mimes:jpg,jpeg,pdf|max:1024',
        ], [
            'alasan.min' => 'Alasan harus diisi minimal 10 karakter',
            'tanggal_awal.before_or_equal' => 'Tanggal awal tidak boleh lebih dari tanggal akhir',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir tidak boleh kurang dari tanggal awal',
            'berkas.mimes' => 'Berkas harus berupa file JPG, JPEG, atau PDF',
            'berkas.max' => 'Ukuran maksimal berkas adalah 1 MB',
        ]);

        $user = Auth::user();

        // Check if there are already pending izin requests for the same dates
        $existingRequest = PengajuanIjin::where('user_id', $user->id)
            ->where('status', 'menunggu')
            ->where(function ($query) use ($request) {
                $query->whereBetween('tanggal_awal', [$request->tanggal_awal, $request->tanggal_akhir])
                    ->orWhereBetween('tanggal_akhir', [$request->tanggal_awal, $request->tanggal_akhir])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('tanggal_awal', '<=', $request->tanggal_awal)
                            ->where('tanggal_akhir', '>=', $request->tanggal_akhir);
                    });
            })
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan ijin yang pending untuk tanggal tersebut!')->withInput();
        }

        // Handle file upload
        $berkasPath = null;
        if ($request->hasFile('berkas') && $request->file('berkas')->isValid()) {
            $file = $request->file('berkas');
            $fileName = time() . '_' . $user->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Store in private storage
            $berkasPath = $file->storeAs('private/izin', $fileName);
        }

        PengajuanIjin::create([
            'user_id' => $user->id,
            'jenis_izin' => $request->jenis_izin,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'alasan' => $request->alasan,
            'berkas' => $berkasPath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('izin.saya')->with('success', 'Pengajuan ijin berhasil dikirim! Menunggu persetujuan.');
    }

    /**
     * Approve an izin request.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string',
        ]);

        $pengajuanIjin = PengajuanIjin::findOrFail($id);

        if ($pengajuanIjin->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pengajuan ijin sudah diproses sebelumnya!');
        }

        $pengajuanIjin->update([
            'status' => 'diterima',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'catatan' => $request->catatan,
        ]);

        // Create presensi records for the izin dates
        $tanggal = $pengajuanIjin->tanggal_awal;
        while ($tanggal->lte($pengajuanIjin->tanggal_akhir)) {
            // Check if presensi already exists for this date
            $existingPresensi = \App\Models\Presensi::where('user_id', $pengajuanIjin->user_id)
                ->where('tanggal', $tanggal)
                ->first();

            if (!$existingPresensi) {
                \App\Models\Presensi::create([
                    'user_id' => $pengajuanIjin->user_id,
                    'tanggal' => $tanggal,
                    'status' => $pengajuanIjin->jenis_izin,
                    'keterangan' => 'Izin disetujui oleh: ' . Auth::user()->name,
                ]);
            } else {
                // Update existing presensi
                $existingPresensi->update([
                    'status' => $pengajuanIjin->jenis_izin,
                    'keterangan' => 'Izin disetujui oleh: ' . Auth::user()->name,
                ]);
            }

            $tanggal = $tanggal->addDay();
        }

        return redirect()->back()->with('success', 'Pengajuan ijin telah disetujui!');
    }

    /**
     * Reject an izin request.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|min:5',
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi',
            'catatan.min' => 'Alasan penolakan harus diisi minimal 5 karakter',
        ]);

        $pengajuanIjin = PengajuanIjin::findOrFail($id);

        if ($pengajuanIjin->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pengajuan ijin sudah diproses sebelumnya!');
        }

        $pengajuanIjin->update([
            'status' => 'ditolak',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'catatan' => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Pengajuan ijin telah ditolak!');
    }

    /**
     * Get count of pending izin requests (for notifications).
     */
    public static function getPendingCount()
    {
        $user = Auth::user();

        $query = PengajuanIjin::where('status', 'menunggu');

        if ($user->isWaliKelas() && $user->kelas) {
            $siswaIds = Siswa::where('kelas', $user->kelas)->pluck('user_id');
            $query->whereIn('user_id', $siswaIds);
        } elseif ($user->isSiswa()) {
            return 0; // Students don't need notifications for their own requests
        }

        return $query->count();
    }

    /**
     * Download berkas file.
     */
    public function downloadBerkas($id)
    {
        $pengajuanIjin = PengajuanIjin::findOrFail($id);

        if (!$pengajuanIjin->berkas) {
            return redirect()->back()->with('error', 'Berkas tidak ditemukan!');
        }

        // Check if file exists in storage
        if (!Storage::exists($pengajuanIjin->berkas)) {
            return redirect()->back()->with('error', 'Berkas tidak ditemukan di server!');
        }

        // Get file content using Storage facade
        $fileContent = Storage::get($pengajuanIjin->berkas);

        // Determine content type based on file extension
        $extension = pathinfo($pengajuanIjin->berkas, PATHINFO_EXTENSION);
        $contentType = match(strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'pdf' => 'application/pdf',
            default => 'application/octet-stream',
        };

        // Get original filename from storage path
        $fileName = basename($pengajuanIjin->berkas);

        return response($fileContent, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }
}

