<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int|null $user_id
 * @property User|null $user
 * @property int $id
 * @property string $nis
 * @property string $nama
 * @property string $kelas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $foto
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereUserId($value)
 * @mixin \Eloquent
 */
class Siswa extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'user_id',
        'foto',
    ];

    /**
     * Get the user that owns the siswa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
