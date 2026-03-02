<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Libur extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tanggal_mulai',
        'tanggal_akhir',
        'nama',
        'jenis',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_akhir' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if today is a holiday.
     */
    public static function isHariLibur($tanggal = null): bool
    {
        $tanggal = $tanggal ?? now()->toDateString();

        return static::where('is_active', true)
            ->where(function($query) use ($tanggal) {
                $query->where('tanggal_mulai', '<=', $tanggal)
                    ->where(function($q) use ($tanggal) {
                        $q->where('tanggal_akhir', '>=', $tanggal)
                          ->orWhereNull('tanggal_akhir');
                    });
            })
            ->exists();
    }

    /**
     * Get today's holiday if exists.
     */
    public static function getLiburHariIni($tanggal = null)
    {
        $tanggal = $tanggal ?? now()->toDateString();

        return static::where('is_active', true)
            ->where('tanggal_mulai', '<=', $tanggal)
            ->where(function($query) use ($tanggal) {
                $query->where('tanggal_akhir', '>=', $tanggal)
                      ->orWhereNull('tanggal_akhir');
            })
            ->first();
    }

    /**
     * Get all active holidays.
     */
    public static function getLiburAktif()
    {
        return static::where('is_active', true)
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
    }

    /**
     * Check if this is a multi-day holiday.
     */
    public function isMultiDay(): bool
    {
        return !is_null($this->tanggal_akhir) && $this->tanggal_akhir->ne($this->tanggal_mulai);
    }

    /**
     * Get formatted date range.
     */
    public function getTanggalRangeAttribute(): string
    {
        if ($this->tanggal_akhir && $this->tanggal_akhir->ne($this->tanggal_mulai)) {
            return $this->tanggal_mulai->format('d M') . ' - ' . $this->tanggal_akhir->format('d M Y');
        }
        return $this->tanggal_mulai->format('d M Y');
    }

    /**
     * Check if date is a national holiday.
     */
    public function isNasional(): bool
    {
        return $this->jenis === 'nasional';
    }

    /**
     * Check if date is a school holiday.
     */
    public function isSekolah(): bool
    {
        return $this->jenis === 'sekolah';
    }

    /**
     * Get number of holiday days.
     */
    public function getHariCountAttribute(): int
    {
        if ($this->tanggal_akhir) {
            return $this->tanggal_mulai->diffInDays($this->tanggal_akhir) + 1;
        }
        return 1;
    }
}

