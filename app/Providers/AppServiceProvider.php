<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Define Gate for Super Admin (kesiswaan)
        Gate::define('isSuperAdmin', function ($user) {
            return $user->isSuperAdmin();
        });

        // Define Gate for non-siswa users (admin, wali_kelas, bk, kesiswaan)
        Gate::define('isNotSiswa', function ($user) {
            return !$user->isSiswa();
        });
    }
}
