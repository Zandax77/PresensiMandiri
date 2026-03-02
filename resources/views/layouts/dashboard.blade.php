<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Presensi Mandiri') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Inter', 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                background-color: #f8fafc;
                color: #1e293b;
                line-height: 1.6;
                min-height: 100vh;
            }

            Side /*bar Styles */
            .app-container {
                display: flex;
                min-height: 100vh;
            }

            .sidebar {
                width: 280px;
                background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
                color: white;
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                overflow-y: auto;
                z-index: 1000;
                transition: transform 0.3s ease;
            }

            .sidebar-header {
                padding: 1.5rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .sidebar-logo {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .sidebar-logo-icon {
                width: 40px;
                height: 40px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-logo-text {
                font-size: 1.125rem;
                font-weight: 700;
            }

            .sidebar-user {
                margin-top: 1rem;
                padding: 0.75rem;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 8px;
            }

            .sidebar-user-name {
                font-weight: 600;
                font-size: 0.9375rem;
            }

            .sidebar-user-role {
                font-size: 0.75rem;
                color: #94a3b8;
                margin-top: 0.25rem;
            }

            .sidebar-nav {
                padding: 1rem 0;
            }

            .sidebar-nav-title {
                padding: 0.5rem 1.5rem;
                font-size: 0.6875rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #64748b;
                margin-top: 1rem;
            }

            .sidebar-nav-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1.5rem;
                color: #cbd5e1;
                text-decoration: none;
                font-size: 0.9375rem;
                font-weight: 500;
                transition: all 0.2s;
                border-left: 3px solid transparent;
            }

            .sidebar-nav-item:hover {
                background: rgba(255, 255, 255, 0.1);
                color: white;
            }

            .sidebar-nav-item.active {
                background: rgba(102, 126, 234, 0.2);
                color: #667eea;
                border-left-color: #667eea;
            }

            .sidebar-nav-item svg {
                width: 20px;
                height: 20px;
                flex-shrink: 0;
            }

            .sidebar-nav-divider {
                height: 1px;
                background: rgba(255, 255, 255, 0.1);
                margin: 1rem 1.5rem;
            }

            .sidebar-logout {
                margin: 1rem;
                padding: 0.75rem 1rem;
                background: rgba(239, 68, 68, 0.2);
                color: #fca5a5;
                border: none;
                border-radius: 8px;
                font-size: 0.9375rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                width: calc(100% - 2rem);
            }

            .sidebar-logout:hover {
                background: rgba(239, 68, 68, 0.3);
                color: white;
            }

            .sidebar-logout svg {
                width: 20px;
                height: 20px;
            }

            /* Main Content */
            .main-content {
                flex: 1;
                margin-left: 280px;
                min-height: 100vh;
                transition: margin-left 0.3s ease;
            }

            /* Mobile Header */
            .mobile-header {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 60px;
                background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
                color: white;
                z-index: 999;
                padding: 0 1rem;
                align-items: center;
                justify-content: space-between;
            }

            .mobile-menu-btn {
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                padding: 0.5rem;
            }

            .mobile-menu-btn svg {
                width: 24px;
                height: 24px;
            }

            .mobile-logo {
                font-weight: 700;
                font-size: 1rem;
            }

            /* Sidebar Overlay */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            /* Responsive */
            @media (max-width: 1024px) {
                .sidebar {
                    transform: translateX(-100%);
                }

                .sidebar.open {
                    transform: translateX(0);
                }

                .main-content {
                    margin-left: 0;
                }

                .mobile-header {
                    display: flex;
                }

                .sidebar-overlay.show {
                    display: block;
                }
            }

            /* Common Styles */
            .container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 1.5rem;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-size: 0.9375rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
                border: none;
                text-decoration: none;
            }

            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
            }

            .alert {
                padding: 1rem;
                border-radius: 8px;
                margin-bottom: 1.5rem;
                font-size: 0.875rem;
            }

            .alert-success {
                background: #dcfce7;
                color: #166534;
                border: 1px solid #86efac;
            }

            .alert-error {
                background: #fee2e2;
                color: #991b1b;
                border: 1px solid #fca5a5;
            }

            .error-message {
                color: #dc2626;
                font-size: 0.8125rem;
                margin-top: 0.375rem;
            }

            /* Content wrapper for mobile padding */
            .content-wrapper {
                padding-top: 60px;
            }

            @media (max-width: 1024px) {
                .content-wrapper {
                    padding-top: 70px;
                }
            }

            @media (max-width: 480px) {
                .container {
                    padding: 1rem;
                }
            }
        </style>
    @endif

    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <button class="mobile-menu-btn" onclick="toggleSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="mobile-logo">Presensi Mandiri</div>
            <div style="width: 40px;"></div>
        </div>

        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="sidebar-logo-text">Presensi<br>Mandiri</span>
                </div>
                <div class="sidebar-user">
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">{{ auth()->user()->getRoleLabelAttribute() }}</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-nav-title">Menu Utama</div>

                <!-- Dashboard - Only for non-siswa -->
                @if(!auth()->user()->isSiswa())
                <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                @endif

                <!-- Presensi - Only for siswa -->
                @if(auth()->user()->isSiswa())
                <a href="{{ route('presensi.index') }}" class="sidebar-nav-item {{ request()->routeIs('presensi.index') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Presensi Harian
                </a>

                <!-- Pengajuan Ijin Saya - Only for siswa -->
                <a href="{{ route('izin.saya') }}" class="sidebar-nav-item {{ request()->routeIs('izin.saya') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Pengajuan Ijin Saya
                </a>
                @endif

                @if(auth()->user()->isSuperAdmin())
                    <div class="sidebar-nav-title">Administrasi</div>

                    <a href="{{ route('sekolah.index') }}" class="sidebar-nav-item {{ request()->routeIs('sekolah.index') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Pengaturan Sekolah
                    </a>

                    <a href="{{ route('users.index') }}" class="sidebar-nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Kelola User
                    </a>

                    <a href="{{ route('rekap-presensi.index') }}" class="sidebar-nav-item {{ request()->routeIs('rekap-presensi.index') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Rekap Presensi
                    </a>
                @endif

                @if(auth()->user()->isWaliKelas())
                    <div class="sidebar-nav-title">Administrasi</div>

                    <a href="{{ route('izin.index') }}" class="sidebar-nav-item {{ request()->routeIs('izin.index') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Kelola Ijin
                        @php
                            $pendingIzin = \App\Http\Controllers\KendaliIzin::getPendingCount();
                        @endphp
                        @if($pendingIzin > 0)
                            <span style="margin-left: auto; background: #ef4444; color: white; font-size: 0.75rem; padding: 0.125rem 0.5rem; border-radius: 9999px;">{{ $pendingIzin }}</span>
                        @endif
                    </a>

                    <a href="{{ route('wali-kelas.presensi') }}" class="sidebar-nav-item {{ request()->routeIs('wali-kelas.presensi') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Presensi Kelas
                    </a>

                    <a href="{{ route('rekap-presensi.index') }}" class="sidebar-nav-item {{ request()->routeIs('rekap-presensi.index') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Rekap Presensi
                    </a>

                    <a href="{{ route('wali-kelas.siswa') }}" class="sidebar-nav-item {{ request()->routeIs('wali-kelas.siswa') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Kelola Siswa
                    </a>
                @endif

                <div class="sidebar-nav-divider"></div>

                <a href="{{ route('password.change.form') }}" class="sidebar-nav-item {{ request()->routeIs('password.change.form') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    Ganti Password
                </a>
            </nav>

            <form method="POST" action="{{ route('logout') }}" style="padding: 0 1rem 1rem;">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        // Close sidebar when pressing escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                if (sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('show');
                }
            }
        });
    </script>

    @yield('scripts')
</body>
</html>

