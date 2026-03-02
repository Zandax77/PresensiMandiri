<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
