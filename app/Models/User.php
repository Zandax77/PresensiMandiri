<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property int $is_active
 * @property string|null $kelas
 * @property-read string $role_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PengajuanIjin> $izinMenunggu
 * @property-read int|null $izin_menunggu_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PengajuanIjin> $pengajuanIjin
 * @property-read int|null $pengajuan_ijin_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Presensi> $presensis
 * @property-read int|null $presensis_count
 * @property-read \App\Models\Siswa|null $siswa
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kelas',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the presensi records for the user.
     */
    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }

    /**
     * Get the izin requests submitted by the user.
     */
    public function pengajuanIjin(): HasMany
    {
        return $this->hasMany(PengajuanIjin::class);
    }

    /**
     * Get the izin requests that need to be approved by this user.
     */
    public function izinMenunggu(): HasMany
    {
        return $this->hasMany(PengajuanIjin::class, 'approved_by')->where('status', 'menunggu');
    }

    /**
     * Get the siswa profile for the user.
     */
    public function siswa(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Siswa::class);
    }

    /**
     * Check if the user is a student (siswa).
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /**
     * Check if the user is admin/non-siswa (wali kelas, BK, or kesiswaan).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['wali_kelas', 'bk', 'kesiswaan']);
    }

    /**
     * Check if the user is a super admin (kesiswaan).
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if the user is kesiswaan.
     */
    public function isKesiswaan(): bool
    {
        return $this->role === 'kesiswaan';
    }

    /**
     * Check if the user is BK (Bimbingan Konseling).
     */
    public function isBK(): bool
    {
        return $this->role === 'bk';
    }

    /**
     * Check if the user account is active.
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Check if the user account is active (for super_admin, always true).
     */
    public function isAccountActive(): bool
    {
        // Super admin tidak memerlukan aktivasi
        if ($this->role === 'super_admin') {
            return true;
        }
        return (bool) $this->is_active;
    }

    /**
     * Get the role label in Indonesian.
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Admin',
            'siswa' => 'Siswa',
            'wali_kelas' => 'Wali Kelas',
            'bk' => 'BK',
            'kesiswaan' => 'Kesiswaan',
            default => 'Unknown',
        };
    }

    /**
     * Check if the user is a wali kelas.
     */
    public function isWaliKelas(): bool
    {
        return $this->role === 'wali_kelas';
    }

    /**
     * Get all available classes from siswa table.
     */
    public static function getAvailableClasses(): array
    {
        return Siswa::whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->pluck('kelas')
            ->sort()
            ->toArray();
    }

    /**
     * Get students in the same class (for wali kelas).
     */
    public function getSiswaInKelas()
    {
        if (!$this->kelas) {
            return collect();
        }
        return Siswa::where('kelas', $this->kelas)->get();
    }

    /**
     * Get presensi records for students in the same class (for wali kelas).
     */
    public function getPresensiInKelas($tanggal = null)
    {
        if (!$this->kelas) {
            return collect();
        }

        $siswaIds = Siswa::where('kelas', $this->kelas)->pluck('user_id');

        $query = Presensi::whereIn('user_id', $siswaIds);

        if ($tanggal) {
            $query->where('tanggal', $tanggal);
        }

        return $query->get();
    }
}
