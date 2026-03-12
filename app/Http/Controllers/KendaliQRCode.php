<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KendaliQRCode extends Controller
{
    /**
     * Display a list of students for QR code generation (Super Admin only).
     */
    public function index(Request $request)
    {
        // Only super admin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Get filter parameters
        $kelasDipilih = $request->get('kelas', '');

        // Get available classes
        $availableClasses = User::getAvailableClasses();

        // Get students based on filter
        $siswaQuery = Siswa::with('user');

        if ($kelasDipilih) {
            $siswaQuery->where('kelas', $kelasDipilih);
        }

        $siswaList = $siswaQuery->orderBy('kelas')->orderBy('nama')->get();

        return view('qr-code.index', compact('siswaList', 'availableClasses', 'kelasDipilih'));
    }

    /**
     * Generate QR Code ID Card for a student based on NIS.
     */
    public function generate($nis)
    {
        // Only super admin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Find student by NIS
        $siswa = Siswa::with('user')->where('nis', $nis)->first();

        if (!$siswa) {
            abort(404, 'Siswa dengan NIS tersebut tidak ditemukan.');
        }

        // Get school data
        $sekolah = Sekolah::getSekolah();

        // Generate QR code data - encode NIS as JSON for flexibility
        $qrData = json_encode([
            'nis' => $siswa->nis,
            'nama' => $siswa->nama,
            'kelas' => $siswa->kelas,
            'type' => 'PRESENSI_MANDIRI',
        ]);

        return view('qr-code.id-card', compact('siswa', 'sekolah', 'qrData'));
    }

    /**
     * Upload photo for a student.
     */
    public function uploadPhoto(Request $request)
    {
        // Only super admin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'nis' => 'required|exists:siswas,nis',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find student by NIS
        $siswa = Siswa::where('nis', $request->nis)->first();

        // Delete old photo if exists
        if ($siswa->foto && Storage::exists('public/' . $siswa->foto)) {
            Storage::delete('public/' . $siswa->foto);
        }

        // Store new photo
        $path = $request->file('foto')->store('siswa-foto', 'public');

        // Update siswa record
        $siswa->update(['foto' => $path]);

        return redirect()->back()->with('success', 'Foto siswa berhasil diupload!');
    }

    /**
     * Delete photo for a student.
     */
    public function deletePhoto(Request $request)
    {
        // Only super admin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'nis' => 'required|exists:siswas,nis',
        ]);

        // Find student by NIS
        $siswa = Siswa::where('nis', $request->nis)->first();

        // Delete photo if exists
        if ($siswa->foto && Storage::exists('public/' . $siswa->foto)) {
            Storage::delete('public/' . $siswa->foto);
            $siswa->update(['foto' => null]);
            return redirect()->back()->with('success', 'Foto siswa berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Tidak ada foto untuk dihapus.');
    }
}

