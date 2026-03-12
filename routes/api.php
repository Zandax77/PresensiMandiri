<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\ConfigController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


// Public routes - Authentication
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Public routes - Config (no auth required)
Route::get('config', [ConfigController::class, 'index']);
Route::get('config/jam-presensi', [ConfigController::class, 'jamPresensi']);
Route::get('config/lokasi', [ConfigController::class, 'lokasi']);

// Protected routes - require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);

    // Presensi routes
    Route::get('presensi', [PresensiController::class, 'index']);
    Route::post('presensi', [PresensiController::class, 'store']);
    Route::get('presensi/history', [PresensiController::class, 'history']);
    Route::get('presensi/history-siswa', [PresensiController::class, 'historySiswa']);
});
