@extends('layouts.auth')

@section('title', 'Presensi Mandiri')

@section('styles')
<style>
    .presensi-container {
        min-height: 100vh;
        min-height: 100dvh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1.5rem 0.75rem;
        padding-bottom: calc(1.5rem + env(safe-area-inset-bottom, 0px));
        padding-bottom: calc(1.5rem + constant(safe-area-inset-bottom, 0px));
    }

    .presensi-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 420px;
        width: 100%;
        margin: 0 auto;
        overflow: hidden;
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

    .presensi-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.75rem 1.5rem;
        text-align: center;
    }

    .presensi-logo {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        overflow: hidden;
    }

    .presensi-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: transparent;
    }

    .user-name {
        font-size: 1.375rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        word-wrap: break-word;
    }

    .user-kelas {
        font-size: 0.9375rem;
        opacity: 0.9;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .presensi-subtitle {
        opacity: 0.9;
        font-size: 0.8125rem;
    }

    .datetime-display {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 0.875rem;
        margin-top: 1rem;
    }

    .current-time {
        font-size: 2.25rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        line-height: 1.2;
    }

    .current-date {
        font-size: 0.9375rem;
        opacity: 0.9;
        margin-top: 0.25rem;
    }

    .presensi-body {
        padding: 1.5rem;
    }

    .status-info {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.25rem;
        text-align: center;
    }

    .status-info h3 {
        color: #166534;
        font-size: 0.9375rem;
        font-weight: 600;
        margin-bottom: 0.375rem;
    }

    .status-info p {
        color: #15803d;
        font-size: 0.8125rem;
    }

    .status-sudah {
        background: #dcfce7;
        border-color: #86efac;
    }

    .status-sudah h3 {
        color: #166534;
    }

    .status-belum {
        background: #fef3c7;
        border-color: #fcd34d;
    }

    .status-belum h3 {
        color: #92400e;
    }

    .jam-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .jam-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 0.875rem;
        text-align: center;
    }

    .jam-card-label {
        font-size: 0.6875rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.375rem;
    }

    .jam-card-time {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.3;
    }

    .jam-card-status {
        font-size: 0.6875rem;
        margin-top: 0.25rem;
    }

    .jam-card-status.aktif {
        color: #667eea;
    }

    .jam-card-status.tidak-aktif {
        color: #94a3b8;
    }

    .location-info {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.25rem;
    }

    .location-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.625rem;
    }

    .location-icon {
        width: 18px;
        height: 18px;
        color: #667eea;
        flex-shrink: 0;
    }

    .location-title {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #1e293b;
    }

    .location-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
        word-break: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .location-status.berhasil {
        color: #667eea;
    }

    .location-status.gagal {
        color: #dc2626;
    }

    .location-radius {
        font-size: 0.6875rem;
        color: #64748b;
        margin-top: 0.5rem;
    }

    .btn-presensi {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        width: 100%;
        padding: 1rem 1.25rem;
        border: none;
        border-radius: 12px;
        font-size: 0.9375rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 0.625rem;
        min-height: 56px;
    }

    .btn-datang {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-datang:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    }

    .btn-datang:active:not(:disabled) {
        transform: translateY(0);
    }

    .btn-datang:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .btn-pulang {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .btn-pulang:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.4);
    }

    .btn-pulang:active:not(:disabled) {
        transform: translateY(0);
    }

    .btn-pulang:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .btn-icon {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
    }

    .btn-back {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.875rem;
        background: #f1f5f9;
        color: #64748b;
        border: none;
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        margin-top: 0.5rem;
        min-height: 48px;
    }

    .btn-back:hover {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-back:active {
        transform: scale(0.98);
    }

    .btn-izin {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-izin:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .alert {
        padding: 0.875rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 0.8125rem;
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

    .hidden {
        display: none;
    }

    /* Tablet */
    @media (max-width: 480px) {
        .presensi-container {
            padding: 1.25rem 0.5rem;
            padding-bottom: calc(1.25rem + env(safe-area-inset-bottom, 0px));
            padding-bottom: calc(1.25rem + constant(safe-area-inset-bottom, 0px));
        }

        .presensi-header {
            padding: 1.5rem 1.25rem;
        }

        .presensi-body {
            padding: 1.25rem;
        }

        .current-time {
            font-size: 2rem;
        }

        .user-name {
            font-size: 1.25rem;
        }

        .user-kelas {
            font-size: 0.875rem;
        }
    }

    /* Large Phones */
    @media (max-width: 400px) {
        .presensi-container {
            padding: 1rem 0.5rem;
            padding-bottom: calc(1rem + env(safe-area-inset-bottom, 0px));
            padding-bottom: calc(1rem + constant(safe-area-inset-bottom, 0px));
        }

        .presensi-card {
            border-radius: 16px;
        }

        .presensi-header {
            padding: 1.25rem 1rem;
        }

        .presensi-logo {
            width: 48px;
            height: 48px;
            border-radius: 12px;
        }

        .presensi-logo svg {
            width: 24px;
            height: 24px;
        }

        .user-name {
            font-size: 1.125rem;
        }

        .presensi-subtitle {
            font-size: 0.75rem;
        }

        .datetime-display {
            padding: 0.75rem;
            border-radius: 10px;
        }

        .current-time {
            font-size: 1.75rem;
        }

        .current-date {
            font-size: 0.8125rem;
        }

        .presensi-body {
            padding: 1rem;
        }

        .status-info {
            padding: 0.875rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .status-info h3 {
            font-size: 0.875rem;
        }

        .status-info p {
            font-size: 0.75rem;
        }

        .jam-info {
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .jam-card {
            padding: 0.75rem;
            border-radius: 10px;
        }

        .jam-card-time {
            font-size: 0.9375rem;
        }

        .location-info {
            padding: 0.875rem;
            margin-bottom: 1rem;
        }

        .location-status {
            font-size: 0.75rem;
        }

        .location-radius {
            font-size: 0.625rem;
        }

        .btn-presensi {
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
        }

        .btn-icon {
            width: 20px;
            height: 20px;
        }

        .btn-back {
            padding: 0.75rem;
            font-size: 0.75rem;
            border-radius: 8px;
        }

        .alert {
            padding: 0.75rem;
            font-size: 0.75rem;
        }
    }

    /* Small Phones (iPhone SE, etc) */
    @media (max-width: 360px) {
        .presensi-container {
            padding: 0.75rem 0.375rem;
            padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px));
            padding-bottom: calc(0.75rem + constant(safe-area-inset-bottom, 0px));
        }

        .presensi-header {
            padding: 1rem 0.875rem;
        }

        .presensi-logo {
            width: 44px;
            height: 44px;
        }

        .user-name {
            font-size: 1rem;
        }

        .user-kelas {
            font-size: 0.8125rem;
        }

        .current-time {
            font-size: 1.5rem;
        }

        .current-date {
            font-size: 0.75rem;
        }

        .presensi-body {
            padding: 0.875rem;
        }

        .status-info {
            padding: 0.75rem;
        }

        .status-info h3 {
            font-size: 0.8125rem;
        }

        .jam-card-label {
            font-size: 0.625rem;
        }

        .jam-card-time {
            font-size: 0.8125rem;
        }

        .btn-presensi {
            font-size: 0.8125rem;
            padding: 0.75rem;
            min-height: 52px;
        }
    }

    /* Landscape mode for phones */
    @media (max-height: 500px) and (orientation: landscape) {
        .presensi-container {
            min-height: auto;
            padding: 0.75rem;
            padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px));
        }

        .presensi-card {
            max-width: 100%;
        }

        .presensi-header {
            padding: 0.75rem 1rem;
        }

        .datetime-display {
            display: inline-block;
            padding: 0.5rem 0.75rem;
            margin-top: 0.5rem;
        }

        .current-time {
            font-size: 1.25rem;
            display: inline;
        }

        .current-date {
            font-size: 0.75rem;
            display: inline;
            margin-left: 0.5rem;
        }

        .presensi-body {
            padding: 1rem;
        }

        .jam-info {
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }

        .btn-presensi {
            padding: 0.625rem 0.75rem;
            min-height: 44px;
        }
    }

    /* Touch optimization - prevent double-tap zoom */
    a, button, .btn-presensi, .btn-back {
        touch-action: manipulation;
    }

    /* Prevent text selection on buttons */
    .btn-presensi, .btn-back {
        -webkit-tap-highlight-color: transparent;
        user-select: none;
        -webkit-user-select: none;
    }

    /* Smoother animations */
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Holiday Badge */
    .holiday-badge {
        display: inline-block;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.6875rem;
        font-weight: 600;
        margin-left: 0.25rem;
    }

    .holiday-badge.nasional {
        background: #fee2e2;
        color: #991b1b;
    }

    .holiday-badge.sekolah {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Install App Button */
    .install-app-container {
        margin-top: 0.75rem;
    }

    .btn-install {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        min-height: 44px;
    }

    .btn-install:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
    }

    .btn-install:active {
        transform: scale(0.98);
    }

    .btn-install svg {
        width: 20px;
        height: 20px;
    }

    .btn-install.hidden {
        display: none;
    }

    /* iOS Install Hint */
    .ios-install-hint {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 0.75rem;
        margin-top: 0.75rem;
        text-align: center;
    }

    .ios-install-hint p {
        color: #166534;
        font-size: 0.75rem;
        margin: 0;
    }

    .ios-install-hint .ios-step {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        margin-top: 0.375rem;
        font-size: 0.6875rem;
        color: #15803d;
    }

    /* Toast Notification */
    .install-toast {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #1e293b;
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 0.875rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .install-toast.show {
        opacity: 1;
        visibility: visible;
        bottom: 30px;
    }

    .install-toast button {
        background: none;
        border: none;
        color: white;
        margin-left: 10px;
        cursor: pointer;
        padding: 0;
    }
</style>
@endsection

@section('content')
<div class="presensi-container">
    <div class="presensi-card">
        <div class="presensi-header">
            <div class="presensi-logo">
                @if($sekolah && $sekolah->logo_url)
                    <img src="{{ $sekolah->logo_url }}" alt="Logo {{ $sekolah->nama }}">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </div>
            @if($sekolah && $sekolah->nama)
            <div class="school-name" style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; opacity: 0.95;">{{ $sekolah->nama }}</div>
            @endif
            <div class="user-name">{{ auth()->user()->name }}</div>
            @if(auth()->user()->siswa && auth()->user()->siswa->kelas)
            <div class="user-kelas">Kelas: {{ auth()->user()->siswa->kelas }}</div>
            @endif
            <div class="presensi-subtitle">Presensi Mandiri</div>

            <div class="datetime-display">
                <div class="current-time" id="currentTime">{{ \Carbon\Carbon::now()->format('H:i:s') }}</div>
                <div class="current-date" id="currentDate">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</div>
            </div>
        </div>

        <div class="presensi-body">
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Status Presensi Hari Ini --}}
            @if($presensiHariIni)
                <div class="status-info status-sudah">
                    <h3>✅ Sudah Presensi</h3>
                    <p>
                        @if($presensiHariIni->jam_datang)
                            Datang: {{ \Carbon\Carbon::parse($presensiHariIni->jam_datang)->format('H:i') }}
                        @endif
                        @if($presensiHariIni->jam_pulang)
                            | Pulang: {{ \Carbon\Carbon::parse($presensiHariIni->jam_pulang)->format('H:i') }}
                        @endif
                    </p>
                </div>
            @elseif(!$isLibur)
                <div class="status-info status-belum">
                    <h3>⏳ Belum Presensi</h3>
                    <p>Silakan lakukan presensi datang</p>
                </div>
            @endif

            {{-- Holiday / Day Off Status --}}
            @if($isLibur)
                <div class="status-info" style="background: #fee2e2; border-color: #fca5a5;">
                    <h3 style="color: #991b1b;">📅 {{ $liburReason }}</h3>
                    <p style="color: #b91c1c;">
                        @if($liburHariIni)
                            <span class="holiday-badge {{ $liburHariIni->jenis }}">
                                {{ $liburHariIni->jenis === 'nasional' ? 'Libur Nasional' : 'Libur Sekolah' }}
                            </span>
                        @endif
                        Presensi dinonaktifkan
                    </p>
                </div>
            @endif

            {{-- Batas Jam Info --}}
            <div class="jam-info">
                <div class="jam-card">
                    <div class="jam-card-label">Batas Datang</div>
                    <div class="jam-card-time">{{ \Carbon\Carbon::parse($config['batas_datang_mulai'])->format('H:i') }} - {{ \Carbon\Carbon::parse($config['batas_datang_akhir'])->format('H:i') }}</div>
                    <div class="jam-card-status aktif">● Aktif</div>
                </div>
                <div class="jam-card">
                    <div class="jam-card-label">Batas Pulang</div>
                    <div class="jam-card-time">{{ \Carbon\Carbon::parse($config['batas_pulang_mulai'])->format('H:i') }} - {{ \Carbon\Carbon::parse($config['batas_pulang_akhir'])->format('H:i') }}</div>
                    <div class="jam-card-status aktif">● Aktif</div>
                </div>
            </div>

            {{-- Location Info --}}
            <div class="location-info">
                <div class="location-header">
                    <svg class="location-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="location-title">Lokasi Anda</span>
                </div>
                <div class="location-status" id="locationStatus">
                    <span id="locationIcon">⏳</span>
                    <span id="locationText">Mendapatkan lokasi...</span>
                </div>
                <div class="location-radius">Radius yang diijinkan: {{ $config['radius_ijin'] }} meter dari titik preset</div>
            </div>

            {{-- Presensi Form --}}
            <form method="POST" action="{{ route('presensi.store') }}" id="presensiForm">
                @csrf
                <input type="hidden" name="tipe" id="tipeInput">
                <input type="hidden" name="latitude" id="latitudeInput">
                <input type="hidden" name="longitude" id="longitudeInput">

                <button type="button" class="btn-presensi btn-datang" id="btnDatang"
                    {{ $presensiHariIni && $presensiHariIni->jam_datang ? 'disabled' : '' }}
                    {{ $isLibur ? 'disabled' : '' }}>
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    {{ $isLibur ? 'Presensi Ditutup' : 'Presensi Datang' }}
                </button>

                <button type="button" class="btn-presensi btn-pulang" id="btnPulang"
                    {{ !$presensiHariIni || !$presensiHariIni->jam_datang || $presensiHariIni->jam_pulang ? 'disabled' : '' }}
                    {{ $isLibur ? 'disabled' : '' }}>
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    {{ $isLibur ? 'Presensi Ditutup' : 'Presensi Pulang' }}
                </button>
            </form>

            {{-- Logout Button for Siswa --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>

            {{-- Link to Izin Page --}}
            <a href="{{ route('izin.saya') }}" class="btn-back btn-izin" style="margin-top: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Pengajuan Ijin
            </a>

            {{-- Install App Button --}}
            <div class="install-app-container" id="installContainer">
                <button type="button" class="btn-install" id="btnInstall" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Install Aplikasi
                </button>

                {{-- iOS Install Hint - shown only on iOS devices --}}
                <div class="ios-install-hint" id="iosInstallHint" style="display: none;">
                    <p>📱 Untuk install di iPhone/iPad:</p>
                    <div class="ios-step">
                        <span>Tap</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        <span>Share</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                        <span>Add to Home Screen</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Install Toast Notification --}}
<div class="install-toast" id="installToast">
    <span id="toastMessage">Aplikasi dapat diinstall!</span>
    <button type="button" onclick="document.getElementById('installToast').classList.remove('show')">✕</button>
</div>

@push('scripts')
<script>
    // PWA Install Handling
    let deferredPrompt;
    const installBtn = document.getElementById('btnInstall');
    const iosHint = document.getElementById('iosInstallHint');

    // Detect iOS
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

    // Listen for beforeinstallprompt event (Android)
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();
        // Stash the event so it can be triggered later
        deferredPrompt = e;

        // Show install button for Android
        if (installBtn) {
            installBtn.style.display = 'flex';
        }
    });

    // Listen for appinstalled event
    window.addEventListener('appinstalled', (e) => {
        // Hide install button after installation
        if (installBtn) {
            installBtn.style.display = 'none';
        }
        // Show success toast
        showToast('Aplikasi berhasil diinstall! 🎉');
        console.log('PWA installed successfully');
    });

    // Handle install button click (Android)
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                // Show the install prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;
                console.log('User response to install prompt:', outcome);
                // Clear the deferredPrompt
                deferredPrompt = null;
                // Hide the button
                installBtn.style.display = 'none';
            }
        });
    }

    // Show iOS install hint if on iOS
    if (isIOS && iosHint) {
        iosHint.style.display = 'block';
    }

    // Function to show toast notification
    function showToast(message) {
        const toast = document.getElementById('installToast');
        const toastMessage = document.getElementById('toastMessage');
        if (toast && toastMessage) {
            toastMessage.textContent = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    }

    // Update time and date
    function updateDateTime() {
        const now = new Date();

        // Format time
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;

        // Format date
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString('id-ID', options);
        document.getElementById('currentDate').textContent = dateString;
    }

    // Update immediately and then every second
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Geolocation
    let userLat = null;
    let userLng = null;

    function showLocationStatus(status, message) {
        const statusEl = document.getElementById('locationStatus');
        const iconEl = document.getElementById('locationIcon');
        const textEl = document.getElementById('locationText');

        statusEl.className = 'location-status ' + status;

        if (status === 'berhasil') {
            iconEl.textContent = '✓';
        } else if (status === 'gagal') {
            iconEl.textContent = '✗';
        } else {
            iconEl.textContent = '⏳';
        }

        textEl.textContent = message;
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    userLat = position.coords.latitude;
                    userLng = position.coords.longitude;

                    document.getElementById('latitudeInput').value = userLat;
                    document.getElementById('longitudeInput').value = userLng;

                    showLocationStatus('berhasil', `Lokasi: ${userLat.toFixed(6)}, ${userLng.toFixed(6)}`);
                },
                function(error) {
                    let message = 'Gagal mendapatkan lokasi';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            message = 'Izin lokasi ditolak';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = 'Lokasi tidak tersedia';
                            break;
                        case error.TIMEOUT:
                            message = 'Waktu habis menunggu lokasi';
                            break;
                    }
                    showLocationStatus('gagal', message);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            showLocationStatus('gagal', 'Browser tidak mendukung geolocation');
        }
    }

    // Get location on page load
    getLocation();

    // Button click handlers
    document.getElementById('btnDatang').addEventListener('click', function() {
        if (!userLat || !userLng) {
            alert('Silakan tunggu hingga lokasi berhasil dideteksi!');
            return;
        }

        if (confirm('Apakah Anda yakin ingin melakukan presensi DATANG?')) {
            document.getElementById('tipeInput').value = 'datang';
            document.getElementById('presensiForm').submit();
        }
    });

    document.getElementById('btnPulang').addEventListener('click', function() {
        if (!userLat || !userLng) {
            alert('Silakan tunggu hingga lokasi berhasil dideteksi!');
            return;
        }

        if (confirm('Apakah Anda yakin ingin melakukan presensi PULANG?')) {
            document.getElementById('tipeInput').value = 'pulang';
            document.getElementById('presensiForm').submit();
        }
    });
</script>
@endpush

