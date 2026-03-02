<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Presensi Mandiri') }} - @yield('title', 'Login')</title>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#667eea">
    <meta name="description" content="Aplikasi Presensi Mandiri untuk Siswa">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Presensi">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Presensi Mandiri">
    <meta name="msapplication-TileColor" content="#667eea">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('icons/icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="128x128" href="{{ asset('icons/icon-128x128.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('icons/icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="384x384" href="{{ asset('icons/icon-384x384.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('icons/icon-512x512.png') }}">

    <!-- Apple Startup Screen -->
    <link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" href="{{ asset('icons/icon-1125x2436.png') }}">
    <link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" href="{{ asset('icons/icon-828x1792.png') }}">
    <link rel="apple-touch-startup-image" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3)" href="{{ asset('icons/icon-1170x2532.png') }}">

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

            .login-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .login-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                width: 100%;
                max-width: 420px;
                padding: 2.5rem;
                animation: slideUp 0.5s ease-out;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .login-header {
                text-align: center;
                margin-bottom: 2rem;
            }

            .login-school-name {
                font-size: 1rem;
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 0.5rem;
                margin-top: 0.5rem;
            }

            .login-logo {
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                font-size: 1.75rem;
                color: white;
                font-weight: 700;
                overflow: hidden;
            }

            .login-logo img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                background: transparent;
            }

            .login-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 0.5rem;
            }

            .login-subtitle {
                color: #64748b;
                font-size: 0.875rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 500;
                color: #374151;
                margin-bottom: 0.5rem;
            }

            .form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                font-size: 0.9375rem;
                transition: all 0.2s;
                background: #f8fafc;
            }

            .form-input:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                background: white;
            }

            .form-input::placeholder {
                color: #94a3b8;
            }

            .form-checkbox-group {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .form-checkbox {
                width: 18px;
                height: 18px;
                accent-color: #667eea;
                cursor: pointer;
            }

            .form-checkbox-label {
                font-size: 0.875rem;
                color: #64748b;
                cursor: pointer;
                user-select: none;
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
                width: 100%;
            }

            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            .forgot-password {
                display: flex;
                justify-content: flex-end;
                margin-bottom: 1.5rem;
            }

            .forgot-password-link {
                font-size: 0.875rem;
                color: #667eea;
                text-decoration: none;
                font-weight: 500;
            }

            .forgot-password-link:hover {
                text-decoration: underline;
            }

            .divider {
                display: flex;
                align-items: center;
                margin: 1.5rem 0;
                color: #94a3b8;
                font-size: 0.875rem;
            }

            .divider::before,
            .divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: #e2e8f0;
            }

            .divider span {
                padding: 0 1rem;
            }

            .register-link {
                text-align: center;
                margin-top: 1.5rem;
                color: #64748b;
                font-size: 0.875rem;
            }

            .register-link a {
                color: #667eea;
                font-weight: 600;
                text-decoration: none;
            }

            .register-link a:hover {
                text-decoration: underline;
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

            .alert-info {
                background: #dbeafe;
                color: #1e40af;
                border: 1px solid #93c5fd;
            }

            .error-message {
                color: #dc2626;
                font-size: 0.8125rem;
                margin-top: 0.375rem;
            }

            .input-icon-wrapper {
                position: relative;
            }

            .input-icon {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                width: 18px;
                height: 18px;
            }

            .form-input-with-icon {
                padding-left: 2.75rem;
            }

            .password-toggle {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                color: #94a3b8;
                padding: 0;
            }

            .password-toggle:hover {
                color: #64748b;
            }

            @media (max-width: 480px) {
                .login-card {
                    padding: 1.5rem;
                }

                .login-title {
                    font-size: 1.25rem;
                }
            }
        </style>
    @endif

    @yield('styles')
</head>
<body>
    @yield('content')

    @stack('scripts')
</body>
</html>

