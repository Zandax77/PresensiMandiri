<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $user_id
 * @property string|null $tanggal
 * @property string|null $jam_datang
 * @property string|null $jam_pulang
 * @property string|null $status
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $keterangan
 * @property User|null $user
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereJamDatang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereJamPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Presensi whereUserId($value)
 * @mixin \Eloquent
 */
class Presensi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_datang',
        'jam_pulang',
        'status',
        'longitude',
        'latitude',
        'keterangan',
    ];

    /**
     * Get the user that owns the presensi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the siswa that owns the presensi.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'user_id', 'user_id');
    }
}
