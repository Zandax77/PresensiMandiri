@extends('layouts.auth')

@section('title', 'Registrasi')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">P</div>
            <h1 class="login-title">Registrasi</h1>
            <p class="login-subtitle">Daftar akun untuk Wali Kelas, BK, atau Kesiswaan</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name Field -->
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-icon-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-input form-input-with-icon"
                        placeholder="Masukkan nama lengkap"
                        value="{{ old('name') }}"
                        required
                        autofocus
                    >
                </div>
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-icon-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input form-input-with-icon"
                        placeholder="nama@email.com"
                        value="{{ old('email') }}"
                        required
                    >
                </div>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jabatan Field -->
            <div class="form-group">
                <label for="jabatan" class="form-label">Jabatan</label>
                <div class="input-icon-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <select
                        id="jabatan"
                        name="jabatan"
                        class="form-input form-input-with-icon"
                        required
                        onchange="toggleKelasField()"
                    >
                        <option value="" disabled selected>Pilih jabatan</option>
                        <option value="wali_kelas">Wali Kelas</option>
                        <option value="bk">BK</option>
                        <option value="kesiswaan">Kesiswaan</option>
                    </select>
                </div>
                @error('jabatan')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kelas Field (Only for Wali Kelas) -->
            <div class="form-group" id="kelas_field" style="display: none;">
                <label for="kelas" class="form-label">Kelas</label>
                <div class="input-icon-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <select
                        id="kelas"
                        name="kelas"
                        class="form-input form-input-with-icon"
                    >
                        <option value="" disabled selected>Pilih kelas</option>
                        @forelse($classes as $kelas)
                            <option value="{{ $kelas }}">{{ $kelas }}</option>
                        @empty
                            <option value="" disabled>Tidak ada kelas tersedia</option>
                        @endforelse
                    </select>
                </div>
                @error('kelas')
                    <p class="error-message">{{ $message }}</p>
                @enderror
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
                </div>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="input-icon-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-input form-input-with-icon"
                        placeholder="Konfirmasi password"
                        required
                    >
                </div>
                @error('password_confirmation')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Register Button -->
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="18" height="18" style="margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Daftar
            </button>
        </form>

        <!-- Login Link -->
        <div style="text-align: center; margin-top: 1.5rem;">
            <p style="color: #64748b; font-size: 0.875rem;">
                Sudah punya akun?
                <a href="{{ route('login') }}" style="color: #10b981; text-decoration: none; font-weight: 600;">
                    Login di sini
                </a>
            </p>
        </div>

        <!-- Info for Siswa -->
        <div style="margin-top: 1.5rem; padding: 1rem; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0;">
            <p style="color: #166534; font-size: 0.875rem; margin: 0;">
                <strong>Catatan:</strong> Untuk siswa, login menggunakan NIS dan password 12345678 tanpa perlu registrasi.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleKelasField() {
    const jabatan = document.getElementById('jabatan').value;
    const kelasField = document.getElementById('kelas_field');

    if (jabatan === 'wali_kelas') {
        kelasField.style.display = 'block';
    } else {
        kelasField.style.display = 'none';
        document.getElementById('kelas').value = '';
    }
}

// Run on page load to handle old values after validation errors
document.addEventListener('DOMContentLoaded', function() {
    toggleKelasField();
});
</script>
@endpush
@endsection

