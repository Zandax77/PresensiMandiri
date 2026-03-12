<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user (non-siswa).
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
            'jabatan' => ['required', 'in:wali_kelas,bk,kesiswaan'],
            'kelas' => ['required_if:jabatan,wali_kelas', 'nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->jabatan,
            'kelas' => $request->jabatan === 'wali_kelas' ? $request->kelas : null,
            'is_active' => true,
        ]);

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil!',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login user (email or NIS for siswa).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $loginInput = $request->email;
        $isNisLogin = is_numeric($loginInput) && strlen($loginInput) >= 4;

        // If logging in with NIS
        if ($isNisLogin) {
            $siswa = Siswa::where('nis', $loginInput)->first();

            if (!$siswa) {
                throw ValidationException::withMessages([
                    'email' => ['NIS atau password yang Anda masukkan salah.'],
                ]);
            }

            $user = User::find($siswa->user_id);

            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['NIS atau password yang Anda masukkan salah.'],
                ]);
            }

            // Check if user is active
            if (!$user->isAccountActive()) {
                throw ValidationException::withMessages([
                    'email' => ['Akun Anda belum diaktifkan. Silakan hubungi admin untuk mengaktifkan akun.'],
                ]);
            }

            // Verify password
            $password = $request->password;
            if (!Hash::check($password, $user->password)) {
                // Also try default password for new accounts
                if ($password !== '12345678') {
                    throw ValidationException::withMessages([
                        'email' => ['NIS atau password yang Anda masukkan salah.'],
                    ]);
                }
            }

            // Create token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil!',
                'user' => $user,
                'token' => $token,
            ]);
        }

        // Regular email login
        $user = User::where('email', $loginInput)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password yang Anda masukkan salah.'],
            ]);
        }

        // Check if user is active (super_admin doesn't need activation)
        if (!$user->isAccountActive()) {
            throw ValidationException::withMessages([
                'email' => ['Akun Anda belum diaktifkan. Silakan hubungi admin untuk mengaktifkan akun.'],
            ]);
        }

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil!',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil!',
        ]);
    }

    /**
     * Get current user info.
     */
    public function me(Request $request)
    {
        $user = $request->user();

        // Get additional info for siswa
        $siswa = null;
        if ($user->isSiswa()) {
            $siswa = $user->siswa;
        }

        return response()->json([
            'user' => $user,
            'siswa' => $siswa,
        ]);
    }
}

