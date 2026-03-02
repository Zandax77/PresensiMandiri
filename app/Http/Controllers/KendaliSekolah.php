<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Libur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KendaliSekolah extends Controller
{
    /**
     * Display the school settings page.
     */
    public function index()
    {
        $sekolah = Sekolah::getSekolah();
        $liburs = Libur::orderBy('tanggal_mulai', 'desc')->paginate(10);

        return view('sekolah.index', compact('sekolah', 'liburs'));
    }

    /**
     * Update school settings.
     */
    public function update(Request $request)
    {
        $sekolah = Sekolah::getSekolah();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $sekolah->nama = $validated['nama'];
        $sekolah->alamat = $validated['alamat'] ?? '';
        $sekolah->latitude = $validated['latitude'];
        $sekolah->longitude = $validated['longitude'];
        $sekolah->telepon = $validated['telepon'] ?? '';
        $sekolah->email = $validated['email'] ?? '';

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($sekolah->logo_path) {
                Storage::delete('public/' . $sekolah->logo_path);
            }

            // Store new logo
            $path = $request->file('logo')->store('logos', 'public');
            $sekolah->logo_path = $path;
        }

        $sekolah->save();

        return redirect()->route('sekolah.index')
            ->with('success', 'Pengaturan sekolah berhasil diperbarui.');
    }

    /**
     * Update jam presensi.
     */
    public function updateJamPresensi(Request $request)
    {
        $sekolah = Sekolah::getSekolah();

        $validated = $request->validate([
            'jam_presensi' => 'required|array',
            'jam_presensi.senin' => 'nullable|array',
            'jam_presensi.senin.datang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.senin.datang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.senin.pulang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.senin.pulang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.selasa' => 'nullable|array',
            'jam_presensi.selasa.datang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.selasa.datang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.selasa.pulang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.selasa.pulang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.rabu' => 'nullable|array',
            'jam_presensi.rabu.datang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.rabu.datang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.rabu.pulang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.rabu.pulang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.kamis' => 'nullable|array',
            'jam_presensi.kamis.datang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.kamis.datang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.kamis.pulang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.kamis.pulang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.jumat' => 'nullable|array',
            'jam_presensi.jumat.datang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.jumat.datang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.jumat.pulang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.jumat.pulang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.sabtu' => 'nullable|array',
            'jam_presensi.sabtu.datang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.sabtu.datang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.sabtu.pulang_mulai' => 'nullable|date_format:H:i',
            'jam_presensi.sabtu.pulang_akhir' => 'nullable|date_format:H:i',
            'jam_presensi.minggu' => 'nullable',
        ]);

        $jamPresensi = [];
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

        foreach ($days as $day) {
            if (isset($validated['jam_presensi'][$day]) && is_array($validated['jam_presensi'][$day])) {
                $dayData = $validated['jam_presensi'][$day];
                $jamPresensi[$day] = [
                    'datang_mulai' => $dayData['datang_mulai'] ?? '06:00',
                    'datang_akhir' => $dayData['datang_akhir'] ?? '08:00',
                    'pulang_mulai' => $dayData['pulang_mulai'] ?? '16:00',
                    'pulang_akhir' => $dayData['pulang_akhir'] ?? '18:00',
                ];
            } else {
                $jamPresensi[$day] = null; // No school on this day
            }
        }

        $sekolah->jam_presensi = $jamPresensi;
        $sekolah->save();

        return redirect()->route('sekolah.index')
            ->with('success', 'Jam presensi berhasil diperbarui.');
    }

    /**
     * Remove school logo.
     */
    public function removeLogo()
    {
        $sekolah = Sekolah::getSekolah();

        if ($sekolah->logo_path) {
            Storage::delete('public/' . $sekolah->logo_path);
            $sekolah->logo_path = null;
            $sekolah->save();
        }

        return redirect()->route('sekolah.index')
            ->with('success', 'Logo berhasil dihapus.');
    }

    /**
     * Store a new holiday.
     */
    public function storeLibur(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:nasional,sekolah',
        ]);

        Libur::create($validated);

        return redirect()->route('sekolah.index')
            ->with('success', 'Libur berhasil ditambahkan.');
    }

    /**
     * Update holiday status (activate/deactivate).
     */
    public function toggleLibur(Libur $libur)
    {
        $libur->is_active = !$libur->is_active;
        $libur->save();

        $status = $libur->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('sekolah.index')
            ->with('success', "Libur berhasil {$status}.");
    }

    /**
     * Delete a holiday.
     */
    public function destroyLibur(Libur $libur)
    {
        $libur->delete();

        return redirect()->route('sekolah.index')
            ->with('success', 'Libur berhasil dihapus.');
    }
}

