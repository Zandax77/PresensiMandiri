<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * Show the registration form for non-siswa users.
     */
    public function showRegistrationForm()
    {
        $classes = User::getAvailableClasses();

        // If no classes available from siswa table, provide default options
        if (empty($classes)) {
            $classes = [
                'X TKJ 1', 'X TKJ 2', 'X RPL 1', 'X RPL 2', 'X MM 1', 'X MM 2', 'X TAV 1', 'X TKR 1',
                'XI TKJ 1', 'XI TKJ 2', 'XI RPL 1', 'XI RPL 2', 'XI MM 1', 'XI MM 2', 'XI TAV 1', 'XI TKR 1',
                'XII TKJ 1', 'XII TKJ 2', 'XII RPL 1', 'XII RPL 2', 'XII MM 1', 'XII MM 2', 'XII TAV 1', 'XII TKR 1'
            ];
        }

        return view('auth.register', compact('classes'));
    }

    /**
     * Handle a registration request for non-siswa users.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'jabatan' => ['required', 'in:wali_kelas,bk,kesiswaan'],
            'kelas' => ['required_if:jabatan,wali_kelas', 'nullable', 'string', 'max:50'],
        ]);

        // Create user with non-siswa role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->jabatan,
            'kelas' => $request->jabatan === 'wali_kelas' ? $request->kelas : null,
            'is_active' => true, // All new users are active by default
        ]);

        // You can optionally log in the user after registration
        // auth()->login($user);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }
}

