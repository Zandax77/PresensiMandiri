<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $tanggal_awal
 * @property \Illuminate\Support\Carbon $tanggal_akhir
 * @property string $jenis_izin
 * @property string $alasan
 * @property string $status
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $berkas
 * @property-read \App\Models\User|null $approver
 * @property-read int $durasi
 * @property-read string $jenis_izin_label
 * @property-read string $status_label
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereAlasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereBerkas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereJenisIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereTanggalAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereTanggalAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanIjin whereUserId($value)
 * @mixin \Eloquent
 */
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

