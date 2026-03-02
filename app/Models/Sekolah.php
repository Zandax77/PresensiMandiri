<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'alamat',
        'logo_path',
        'latitude',
        'longitude',
        'telepon',
        'email',
        'jam_presensi',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'jam_presensi' => 'array',
        ];
    }

    /**
     * Default jam presensi structure.
     */
    public static function defaultJamPresensi(): array
    {
        return [
            'senin' => ['datang_mulai' => '06:00', 'datang_akhir' => '08:00', 'pulang_mulai' => '16:00', 'pulang_akhir' => '18:00'],
            'selasa' => ['datang_mulai' => '06:00', 'datang_akhir' => '08:00', 'pulang_mulai' => '16:00', 'pulang_akhir' => '18:00'],
            'rabu' => ['datang_mulai' => '06:00', 'datang_akhir' => '08:00', 'pulang_mulai' => '16:00', 'pulang_akhir' => '18:00'],
            'kamis' => ['datang_mulai' => '06:00', 'datang_akhir' => '08:00', 'pulang_mulai' => '16:00', 'pulang_akhir' => '18:00'],
            'jumat' => ['datang_mulai' => '06:00', 'datang_akhir' => '08:00', 'pulang_mulai' => '16:00', 'pulang_akhir' => '18:00'],
            'sabtu' => ['datang_mulai' => '07:00', 'datang_akhir' => '09:00', 'pulang_mulai' => '13:00', 'pulang_akhir' => '15:00'],
            'minggu' => null, // No school on Sunday
        ];
    }

    /**
     * Get the school logo URL.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return null;
    }

    /**
     * Check if coordinates are set.
     */
    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Get the first school record (singleton pattern).
     */
    public static function getSekolah()
    {
        return static::firstOrCreate(
            [],
            [
                'nama' => 'SMK Negeri',
                'alamat' => '',
                'jam_presensi' => static::defaultJamPresensi(),
            ]
        );
    }

    /**
     * Get jam presensi for a specific day.
     */
    public function getJamPresensiUntukHari($dayName): ?array
    {
        $dayName = strtolower($dayName);
        $jamPresensi = $this->jam_presensi ?? static::defaultJamPresensi();

        return $jamPresensi[$dayName] ?? null;
    }

    /**
     * Get attendance configuration for today or specific date.
     */
    public function getConfig($tanggal = null): array
    {
        $tanggal = $tanggal ?? now();
        $dayName = strtolower($tanggal->format('l')); // senin, selasa, etc.

        // Map English day to Indonesian
        $dayMap = [
            'monday' => 'senin',
            'tuesday' => 'selasa',
            'wednesday' => 'rabu',
            'thursday' => 'kamis',
            'friday' => 'jumat',
            'saturday' => 'sabtu',
            'sunday' => 'minggu',
        ];

        $dayName = $dayMap[$dayName] ?? $dayName;

        $jam = $this->getJamPresensiUntukHari($dayName);

        if ($jam === null) {
            // No school on this day
            return [
                'is_libur' => true,
                'alasan' => 'Tidak ada sekolah hari ini',
                'batas_datang_mulai' => '00:00:00',
                'batas_datang_akhir' => '00:00:00',
                'batas_pulang_mulai' => '00:00:00',
                'batas_pulang_akhir' => '00:00:00',
                'radius_ijin' => 100,
                'lokasi_kantor' => [
                    'latitude' => $this->latitude ?? -6.200000,
                    'longitude' => $this->longitude ?? 106.816666,
                ],
            ];
        }

        return [
            'is_libur' => false,
            'alasan' => null,
            'batas_datang_mulai' => $jam['datang_mulai'] . ':00',
            'batas_datang_akhir' => $jam['datang_akhir'] . ':00',
            'batas_pulang_mulai' => $jam['pulang_mulai'] . ':00',
            'batas_pulang_akhir' => $jam['pulang_akhir'] . ':00',
            'radius_ijin' => 100,
            'lokasi_kantor' => [
                'latitude' => $this->latitude ?? -6.200000,
                'longitude' => $this->longitude ?? 106.816666,
            ],
        ];
    }
}

