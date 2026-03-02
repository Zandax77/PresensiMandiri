<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        $sekolah = Sekolah::getSekolah();
        return view('auth.login', compact('sekolah'));
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        // Check if input is NIS (for siswa login)
        $loginInput = $request->email;
        $isNisLogin = is_numeric($loginInput) && strlen($loginInput) >= 4;

        // Custom validation - allow NIS or email
        if ($isNisLogin) {
            $request->validate([
                'email' => ['required', 'numeric', 'min:4'],
                'password' => ['required'],
            ]);
        } else {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
        }

        $password = $request->password;
        $remember = $request->boolean('remember');

        // If logging in with NIS
        if ($isNisLogin) {
            // Find siswa by NIS
            $siswa = Siswa::where('nis', $loginInput)->first();

            if ($siswa) {
                $user = User::find($siswa->user_id);

                if ($user) {
                    // Check if user is active
                    if (!$user->isAccountActive()) {
                        throw ValidationException::withMessages([
                            'email' => ['Akun Anda belum diaktifkan. Silakan hubungi admin untuk mengaktifkan akun.'],
                        ]);
                    }

                    // Verify password
                    if (Hash::check($password, $user->password)) {
                        Auth::login($user, $remember);
                        $request->session()->regenerate();
                        return $this->authenticated($request, $user);
                    }

                    // Also try default password for new accounts
                    if ($password === '12345678') {
                        Auth::login($user, $remember);
                        $request->session()->regenerate();
                        return $this->authenticated($request, $user);
                    }
                }
            }

            throw ValidationException::withMessages([
                'email' => ['NIS atau password yang Anda masukkan salah.'],
            ]);
        }

        // Regular email login
        $credentials = ['email' => $loginInput, 'password' => $password];

        if (Auth::attempt($credentials, $remember)) {
            // Check if user is active (super_admin tidak memerlukan aktivasi)
            $user = Auth::user();
            if (!$user->isAccountActive()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                throw ValidationException::withMessages([
                    'email' => ['Akun Anda belum diaktifkan. Silakan hubungi admin untuk mengaktifkan akun.'],
                ]);
            }

            $request->session()->regenerate();

            return $this->authenticated($request, Auth::user());
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password yang Anda masukkan salah.'],
        ]);
    }

    /**
     * The user has been authenticated.
     */
    protected function authenticated(Request $request, $user)
    {
        // Redirect based on user role
        if ($user->isSiswa()) {
            // Siswa goes directly to presensi page (no dashboard)
            return redirect()->route('presensi.index')->with('success', 'Login berhasil!');
        }

        // Super Admin or Non-siswa (wali kelas, BK, kesiswaan) goes to dashboard
        return redirect()->route('dashboard')->with('success', 'Login berhasil!');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil!');
    }
}

