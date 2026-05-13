<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define Gate for Super Admin
        Gate::define('isSuperAdmin', function ($user) {
            return $user->isSuperAdmin();
        });

        // Define Gate for non-siswa users (admin, wali_kelas, bk, kesiswaan)
        Gate::define('isNotSiswa', function ($user) {
            return !$user->isSiswa();
        });

        // Automatically create Super Admin if database is empty or no super admin exists
        try {
            if (Schema::hasTable('users')) {
                if (User::where('role', 'super_admin')->count() === 0) {
                    User::create([
                        'name' => 'Super Admin',
                        'email' => 'admin@presensi.com',
                        'password' => Hash::make('admin123'),
                        'role' => 'super_admin',
                        'is_active' => true,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silently fail if DB is not ready or other issues occur during boot
        }
    }
}
