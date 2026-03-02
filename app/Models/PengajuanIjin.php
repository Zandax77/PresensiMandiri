<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanIjin extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tanggal_awal',
        'tanggal_akhir',
        'jenis_izin',
        'alasan',
        'berkas',
        'status',
        'approved_by',
        'approved_at',
        'catatan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_awal' => 'date',
            'tanggal_akhir' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the izin request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that approved/rejected the izin request.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if the izin request is pending.
     */
    public function isMenunggu(): bool
    {
        return $this->status === 'menunggu';
    }

    /**
     * Check if the izin request is approved.
     */
    public function isDiterima(): bool
    {
        return $this->status === 'diterima';
    }

    /**
     * Check if the izin request is rejected.
     */
    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    /**
     * Get the duration of the izin in days.
     */
    public function getDurasiAttribute(): int
    {
        return $this->tanggal_awal->diffInDays($this->tanggal_akhir) + 1;
    }

    /**
     * Get status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu' => 'Menunggu',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * Get jenis izin label in Indonesian.
     */
    public function getJenisIzinLabelAttribute(): string
    {
        return match($this->jenis_izin) {
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            default => 'Unknown',
        };
    }
}

