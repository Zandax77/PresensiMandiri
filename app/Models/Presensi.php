<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
