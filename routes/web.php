<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\KendaliUtama;
use App\Http\Controllers\KendaliPresensi;
use App\Http\Controllers\KendaliUser;
use App\Http\Controllers\KendaliIzin;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\KendaliSekolah;
use App\Http\Controllers\KendaliQRCode;
use App\Http\Controllers\PresensiRecorder;
use App\Models\Sekolah;

// Apply web middleware to all routes
Route::middleware('web')->group(function () {

    Route::get('/', function () {
        $sekolah = Sekolah::getSekolah();
        return view('auth.login', compact('sekolah'));
    });

    // Guest Routes
    Route::middleware('guest')->group(function () {
        // Login Routes
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login']);

        // Registration Routes (for non-siswa)
        Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [RegisterController::class, 'register']);

        // Forgot Password Routes
        Route::get('password/request', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

        // Reset Password Routes
        Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('password/update', [ResetPasswordController::class, 'reset'])->name('password.update');
    });

    // Authenticated Routes
    Route::middleware('auth')->group(function () {
        // Logout Route
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        // Dashboard Route - only for non-siswa (admin/guru)
        Route::get('dashboard', [KendaliUtama::class, 'index'])->name('dashboard')->middleware('can:isNotSiswa');

        // Dashboard API Route - for real-time data
        Route::get('dashboard/api', [KendaliUtama::class, 'apiData'])->name('dashboard.api')->middleware('can:isNotSiswa');

        // Presensi Routes - only for siswa
        Route::get('presensi', [KendaliPresensi::class, 'index'])->name('presensi.index');
        Route::post('presensi', [KendaliPresensi::class, 'store'])->name('presensi.store');

        // Change Password Route
        Route::get('password/change', [PasswordController::class, 'showChangePasswordForm'])->name('password.change.form');
        Route::post('password/change', [PasswordController::class, 'changePassword'])->name('password.change');

        // User Management Routes - only for super admin (kesiswaan)
        // Super admin manages: super_admin, wali_kelas, bk, kesiswaan (NOT siswa)
        Route::middleware('can:isSuperAdmin')->group(function () {
            Route::get('users', [KendaliUser::class, 'index'])->name('users.index');
            Route::post('users/{user}/activate', [KendaliUser::class, 'activate'])->name('users.activate');
            Route::post('users/{user}/deactivate', [KendaliUser::class, 'deactivate'])->name('users.deactivate');
            Route::post('users/{user}/reset-password', [KendaliUser::class, 'resetPassword'])->name('users.reset-password');
            Route::delete('users/{user}/delete', [KendaliUser::class, 'destroy'])->name('users.destroy');

            // School Settings Routes
            Route::get('sekolah', [KendaliSekolah::class, 'index'])->name('sekolah.index');
            Route::put('sekolah', [KendaliSekolah::class, 'update'])->name('sekolah.update');
            Route::put('sekolah/jam-presensi', [KendaliSekolah::class, 'updateJamPresensi'])->name('sekolah.jam-presensi.update');
            Route::delete('sekolah/logo', [KendaliSekolah::class, 'removeLogo'])->name('sekolah.remove-logo');

// Holiday Management Routes
            Route::post('sekolah/libur', [KendaliSekolah::class, 'storeLibur'])->name('sekolah.libur.store');
            Route::post('sekolah/libur/{libur}/toggle', [KendaliSekolah::class, 'toggleLibur'])->name('sekolah.libur.toggle');
            Route::delete('sekolah/libur/{libur}', [KendaliSekolah::class, 'destroyLibur'])->name('sekolah.libur.destroy');

            // QR Code Generation Routes
            Route::get('qr-code', [KendaliQRCode::class, 'index'])->name('qr-code.index');
            Route::get('qr-code/generate/{nis}', [KendaliQRCode::class, 'generate'])->name('qr-code.generate');
            Route::post('qr-code/upload-photo', [KendaliQRCode::class, 'uploadPhoto'])->name('qr-code.upload-photo');
            Route::post('qr-code/delete-photo', [KendaliQRCode::class, 'deletePhoto'])->name('qr-code.delete-photo');
        });

        // Student Management Routes - for wali kelas
        // Wali kelas manages: siswa in their class only
        // Check if user is wali_kelas
        Route::get('wali-kelas/siswa', [KendaliUser::class, 'siswaIndex'])->name('wali-kelas.siswa');
        Route::get('wali-kelas/presensi', [KendaliUser::class, 'presensiIndex'])->name('wali-kelas.presensi');
        Route::post('wali-kelas/siswa/{user}/activate', [KendaliUser::class, 'activateSiswa'])->name('wali-kelas.siswa.activate');
        Route::post('wali-kelas/siswa/{user}/deactivate', [KendaliUser::class, 'deactivateSiswa'])->name('wali-kelas.siswa.deactivate');
        Route::post('wali-kelas/siswa/{user}/reset-password', [KendaliUser::class, 'resetPasswordSiswa'])->name('wali-kelas.siswa.reset-password');
        Route::post('wali-kelas/siswa/upload-photo', [KendaliUser::class, 'uploadPhotoSiswa'])->name('wali-kelas.siswa.upload-photo');
        Route::post('wali-kelas/siswa/delete-photo', [KendaliUser::class, 'deletePhotoSiswa'])->name('wali-kelas.siswa.delete-photo');

        // Rekap Presensi Routes (for both Super Admin and Wali Kelas)
        Route::get('rekap-presensi', [KendaliUser::class, 'rekapIndex'])->name('rekap-presensi.index');
        Route::get('rekap-presensi/cetak', [KendaliUser::class, 'rekapCetak'])->name('rekap-presensi.cetak');

        // Histori Presensi Routes (for both Super Admin and Wali Kelas)
        Route::get('histori-presensi', [KendaliUser::class, 'historiIndex'])->name('histori-presensi.index');
        Route::get('histori-presensi/{siswa}', [KendaliUser::class, 'historiDetail'])->name('histori-presensi.detail');
        Route::get('histori-presensi/{siswa}/cetak', [KendaliUser::class, 'historiCetak'])->name('histori-presensi.cetak');

        // Presensi Recorder Routes (for Wali Kelas and BK to record student attendance)
        Route::middleware('can:isNotSiswa')->group(function () {
            Route::get('presensi-recorder', [PresensiRecorder::class, 'index'])->name('presensi-recorder.index');
            Route::get('presensi-recorder/search', [PresensiRecorder::class, 'search'])->name('presensi-recorder.search');
            Route::post('presensi-recorder', [PresensiRecorder::class, 'store'])->name('presensi-recorder.store');
            Route::get('presensi-recorder/get-presensi', [PresensiRecorder::class, 'getPresensi'])->name('presensi-recorder.get-presensi');
            Route::post('presensi-recorder/reset', [PresensiRecorder::class, 'reset'])->name('presensi-recorder.reset');
        });

        // Izin Routes
        // Routes for managing izin requests (admin/wali_kelas)
        Route::get('izin', [KendaliIzin::class, 'index'])->name('izin.index');
        Route::post('izin/{id}/approve', [KendaliIzin::class, 'approve'])->name('izin.approve');
        Route::post('izin/{id}/reject', [KendaliIzin::class, 'reject'])->name('izin.reject');

        // Routes for siswa to submit and view their own izin requests
        Route::get('izin/saya', [KendaliIzin::class, 'saya'])->name('izin.saya');
        Route::post('izin', [KendaliIzin::class, 'store'])->name('izin.store');

        // Download berkas route
        Route::get('izin/{id}/berkas', [KendaliIzin::class, 'downloadBerkas'])->name('izin.berkas');
    });

    // Redirect authenticated users based on role
    Route::middleware('auth')->group(function () {
        Route::get('/home', function () {
            $user = auth()->user();
            if ($user->isSiswa()) {
                return redirect()->route('presensi.index');
            }
            return redirect()->route('dashboard');
        })->name('home');
    });
});

