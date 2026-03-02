@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            @if($sekolah && $sekolah->logo_url)
                <div class="login-logo">
                    <img src="{{ $sekolah->logo_url }}" alt="{{ $sekolah->nama }}">
                </div>
            @else
                <div class="login-logo">{{ $sekolah ? \Str::upper(\Str::substr($sekolah->nama, 0, 1)) : 'P' }}</div>
            @endif
            @if($sekolah && $sekolah->nama)
                <p class="login-school-name">{{ $sekolah->nama }}</p>
            @endif
            <h1 class="login-title">Selamat Datang</h1>
            <p class="login-subtitle">Masuk ke akun Presensi Mandiri Anda</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email/NIS Field -->
            <div class="form-group">
                <label for="email" class="form-label">NIS / Email</label>
                <div class="input-icon-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <input
                        type="text"
                        id="email"
                        name="email"
                        class="form-input form-input-with-icon"
                        placeholder="Masukkan NIS atau Email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Siswa login dengan NIS, Guru/Admin login dengan Email</p>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-icon-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input form-input-with-icon"
                        placeholder="Masukkan password"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me Checkbox -->
            <div class="form-group">
                <div class="form-checkbox-group">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="form-checkbox"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label for="remember" class="form-checkbox-label">
                        Ingat saya
                    </label>
                </div>
            </div>

            <!-- Forgot Password Link -->
            <div class="forgot-password">
                <a href="{{ route('password.request') }}" class="forgot-password-link">
                    Lupa password?
                </a>
            </div>

        <!-- Login Button -->
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="18" height="18" style="margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Masuk
            </button>
        </form>

        <!-- Register Link for Non-Siswa -->
        <div style="text-align: center; margin-top: 1.5rem;">
            <p style="color: #64748b; font-size: 0.875rem;">
                Belum punya akun?
                <a href="{{ route('register') }}" style="color: #10b981; text-decoration: none; font-weight: 600;">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }
</script>
@endsection

