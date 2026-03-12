@extends('layouts.dashboard')

@section('title', 'Pengaturan Sekolah')

@section('styles')
<style>
    .settings-container {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 0.9375rem;
        margin-top: 0.25rem;
    }

    /* Alert Messages */
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

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 1.5rem;
    }

    .form-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    /* Tabs */
    .tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 0.5rem;
        flex-wrap: wrap;
    }

    .tab {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-weight: 500;
        color: #64748b;
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tab:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Logo Upload */
    .logo-upload-section {
        margin-bottom: 2rem;
    }

    .logo-preview-container {
        display: flex;
        align-items: flex-start;
        gap: 2rem;
        margin-top: 1rem;
    }

    .logo-preview {
        width: 150px;
        height: 150px;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        overflow: hidden;
    }

    .logo-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .logo-preview-placeholder {
        color: #94a3b8;
        text-align: center;
        padding: 1rem;
    }

    .logo-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .upload-btn-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .upload-btn-wrapper input[type=file] {
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        cursor: pointer;
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
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-danger:hover {
        background: #fecaca;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9375rem;
        color: #1e293b;
        background: white;
        transition: all 0.2s;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Jam Presensi Table */
    .jam-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .jam-table th,
    .jam-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .jam-table th {
        font-weight: 600;
        color: #374151;
        background: #f8fafc;
    }

    .jam-table td.day-name {
        font-weight: 600;
        min-width: 100px;
    }

    .jam-table input[type="time"] {
        width: 100px;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.875rem;
    }

    .jam-table .day-off {
        color: #94a3b8;
        font-style: italic;
    }

    .jam-table tr:hover {
        background: #f8fafc;
    }

    .day-toggle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .toggle-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    /* Holiday Section */
    .holiday-list {
        margin-top: 1.5rem;
    }

    .holiday-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem;
        background: white;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        flex-wrap: wrap;
        gap: 1rem;
        transition: all 0.2s;
    }

    .holiday-item:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        border-color: #c7d2fe;
    }

    .holiday-item.inactive {
        opacity: 0.7;
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .holiday-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        flex: 1;
    }

    .holiday-badge {
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .holiday-badge.nasional {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        color: #7f1d1d;
        border: 1px solid #fca5a5;
    }

    .holiday-badge.sekolah {
        background: linear-gradient(135deg, #c7d2fe 0%, #a5b4fc 100%);
        color: #1e3a8a;
        border: 1px solid #a5b4fc;
    }

    .holiday-date {
        font-weight: 700;
        color: #1e293b;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .holiday-date::before {
        content: "📅";
        font-size: 0.9em;
    }

    .holiday-name {
        color: #475569;
        font-size: 0.95rem;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .holiday-name::before {
        content: "📌";
        font-size: 0.9em;
    }

    .holiday-days {
        color: #667eea;
        font-weight: 600;
        font-size: 0.85rem;
        margin-left: 0.5rem;
    }

    .holiday-status {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        padding: 0.25rem 0.6rem;
        border-radius: 9999px;
        background: #f1f5f9;
        color: #64748b;
        margin-left: 0.75rem;
    }

    .holiday-status.active {
        background: #dcfce7;
        color: #166534;
    }

    .holiday-status.inactive {
        background: #fef3c7;
        color: #92400e;
    }

    .holiday-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-toggle, .btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.85rem;
        font-weight: 600;
        gap: 0.5rem;
        min-width: 100px;
    }

    .btn-toggle {
        background: linear-gradient(135deg, #86efac 0%, #4ade80 100%);
        color: #166534;
    }

    .btn-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 222, 128, 0.3);
    }

    .btn-toggle.active {
        background: linear-gradient(135deg, #fcd34d 0%, #fbbf24 100%);
        color: #92400e;
    }

    .btn-delete {
        background: linear-gradient(135deg, #fca5a5 0%, #f87171 100%);
        color: #7f1d1d;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(248, 113, 113, 0.3);
    }

    /* Add Holiday Form */
    .add-holiday-form {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
        align-items: end;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
    }

    .add-holiday-form .btn-primary {
        grid-column: 1 / -1;
    }

    .add-holiday-form .form-group {
        margin-bottom: 0;
        position: relative;
    }

    .add-holiday-form .form-group small {
        position: absolute;
        bottom: -1.25rem;
        left: 0;
        white-space: nowrap;
    }

    .add-holiday-form .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .add-holiday-form .form-label::before {
        content: "✨";
        font-size: 0.9em;
    }

    .add-holiday-form .form-input,
    .add-holiday-form .form-select {
        background: white;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.85rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        height: 2.625rem;
    }

    .add-holiday-form .form-input:focus,
    .add-holiday-form .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    }

    .add-holiday-form .btn-primary {
        padding: 0.85rem 1.5rem;
        font-weight: 600;
        border-radius: 8px;
        height: fit-content;
    }

    @media (max-width: 768px) {
        .add-holiday-form {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .holiday-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .holiday-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .btn-toggle, .btn-delete {
            min-width: auto;
            flex: 1;
        }
    }

    /* Map Section */
    .map-section {
        margin-top: 2rem;
    }

    #map {
        height: 400px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        z-index: 1;
    }

    .map-hint {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
        padding: 0.75rem;
        background: #eff6ff;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e40af;
    }

    /* Form Actions */
    .form-actions {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #64748b;
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('content')
<div class="settings-container">
    <div class="page-header">
        <h1 class="page-title">Pengaturan Sekolah</h1>
        <p class="page-subtitle">Kelola informasi sekolah, jam presensi per hari, dan hari libur</p>
    </div>

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

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab active" onclick="switchTab('informasi')">Informasi Sekolah</button>
        <button class="tab" onclick="switchTab('jam-presensi')">Jam Presensi</button>
        <button class="tab" onclick="switchTab('libur')">Kelola Libur</button>
    </div>

    <!-- Tab: Informasi Sekolah -->
    <div id="tab-informasi" class="tab-content active">
        <div class="form-card">
            <form action="{{ route('sekolah.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="logo-upload-section">
                    <h2 class="form-title">Logo Sekolah</h2>
                    <div class="logo-preview-container">
                        <div class="logo-preview">
                            @if(!empty($sekolah->logo_path))
                                <img src="{{ asset('storage/' . $sekolah->logo_path) }}" alt="Logo Sekolah" id="logoPreview">
                            @else
                                <div class="logo-preview-placeholder" id="logoPlaceholder">
                                    <p>Belum ada logo</p>
                                </div>
                                <img src="" alt="Logo Preview" id="logoPreview" style="display: none;">
                            @endif
                        </div>
                        <div class="logo-actions">
                            <div class="upload-btn-wrapper">
                                <button type="button" class="btn btn-primary">Upload Logo</button>
                                <input type="file" name="logo" id="logoInput" accept="image/*">
                            </div>
                            @if(!empty($sekolah->logo_path))
                                <button type="button" class="btn btn-danger" onclick="document.getElementById('removeLogoForm').submit();">Hapus Logo</button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Nama Sekolah</label>
                        <input type="text" name="nama" class="form-input" value="{{ old('nama', $sekolah->nama) }}" required>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-textarea">{{ old('alamat', $sekolah->alamat) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="telepon" class="form-input" value="{{ old('telepon', $sekolah->telepon) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $sekolah->email) }}">
                    </div>
                </div>

                <!-- Location Coordinates Section -->
                <div class="map-section" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                    <h2 class="form-title">Lokasi Sekolah</h2>
                    <p class="form-hint" style="margin-bottom: 1.5rem;">Atur lokasi sekolah untuk validasi kehadiran. Klik pada peta atau gunakan tombol untuk mendapatkan lokasi saat ini.</p>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Latitude</label>
                            <input type="text"
                                   name="latitude"
                                   id="latitude"
                                   class="form-input"
                                   value="{{ old('latitude', $sekolah->latitude) }}"
                                   placeholder="Contoh: -6.200000"
                                   readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitude</label>
                            <input type="text"
                                   name="longitude"
                                   id="longitude"
                                   class="form-input"
                                   value="{{ old('longitude', $sekolah->longitude) }}"
                                   placeholder="Contoh: 106.816666"
                                   readonly>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
                        <button type="button" class="btn btn-secondary" onclick="getCurrentLocation()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/></svg>
                            Dapatkan Lokasi Saat Ini
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="clearMarker()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Hapus Marker
                        </button>
                    </div>

                    <div id="map"></div>
                    <div class="map-hint">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <span>Klik pada peta untuk menentukan lokasi sekolah</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
            @if(!empty($sekolah->logo_path))
                <form id="removeLogoForm" action="{{ route('sekolah.remove-logo') }}" method="POST" style="display: none;">
                    @csrf @method('DELETE')
                </form>
            @endif
        </div>
    </div>

    <!-- Tab: Jam Presensi -->
    <div id="tab-jam-presensi" class="tab-content">
        <div class="form-card">
            <form action="{{ route('sekolah.jam-presensi.update') }}" method="POST">
                @csrf
                @method('PUT')

                <h2 class="form-title">Pengaturan Jam Presensi per Hari</h2>
                <p class="form-hint" style="margin-bottom: 1.5rem;">Atur jam presensi untuk setiap hari dalam seminggu. Centang untuk mengaktifkan hari sekolah.</p>

                <table class="jam-table">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam Datang Mulai</th>
                            <th>Jam Datang Akhir</th>
                            <th>Jam Pulang Mulai</th>
                            <th>Jam Pulang Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $days = [
                            'senin' => 'Senin',
                            'selasa' => 'Selasa',
                            'rabu' => 'Rabu',
                            'kamis' => 'Kamis',
                            'jumat' => 'Jumat',
                            'sabtu' => 'Sabtu',
                            'minggu' => 'Minggu'
                        ];

                        $jamPresensi = $sekolah->jam_presensi ?? \App\Models\Sekolah::defaultJamPresensi();
                        @endphp

                        @foreach($days as $key => $label)
                        <tr>
                            <td class="day-name">
                                <div class="day-toggle">
                                    <input type="checkbox"
                                           class="toggle-checkbox"
                                           id="active_{{ $key }}"
                                           {{ isset($jamPresensi[$key]) && $jamPresensi[$key] !== null ? 'checked' : '' }}
                                           onchange="toggleDayFields('{{ $key }}', this.checked)">
                                    <label for="active_{{ $key }}">{{ $label }}</label>
                                </div>
                            </td>
                            <td>
                                <input type="time"
                                       name="jam_presensi[{{ $key }}][datang_mulai]"
                                       id="{{ $key }}_datang_mulai"
                                       value="{{ old('jam_presensi.' . $key . '.datang_mulai', $jamPresensi[$key]['datang_mulai'] ?? '06:00') }}"
                                       {{ isset($jamPresensi[$key]) && $jamPresensi[$key] === null ? 'disabled' : '' }}>
                            </td>
                            <td>
                                <input type="time"
                                       name="jam_presensi[{{ $key }}][datang_akhir]"
                                       id="{{ $key }}_datang_akhir"
                                       value="{{ old('jam_presensi.' . $key . '.datang_akhir', $jamPresensi[$key]['datang_akhir'] ?? '08:00') }}"
                                       {{ isset($jamPresensi[$key]) && $jamPresensi[$key] === null ? 'disabled' : '' }}>
                            </td>
                            <td>
                                <input type="time"
                                       name="jam_presensi[{{ $key }}][pulang_mulai]"
                                       id="{{ $key }}_pulang_mulai"
                                       value="{{ old('jam_presensi.' . $key . '.pulang_mulai', $jamPresensi[$key]['pulang_mulai'] ?? '16:00') }}"
                                       {{ isset($jamPresensi[$key]) && $jamPresensi[$key] === null ? 'disabled' : '' }}>
                            </td>
                            <td>
                                <input type="time"
                                       name="jam_presensi[{{ $key }}][pulang_akhir]"
                                       id="{{ $key }}_pulang_akhir"
                                       value="{{ old('jam_presensi.' . $key . '.pulang_akhir', $jamPresensi[$key]['pulang_akhir'] ?? '18:00') }}"
                                       {{ isset($jamPresensi[$key]) && $jamPresensi[$key] === null ? 'disabled' : '' }}>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Jam Presensi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab: Kelola Libur -->
    <div id="tab-libur" class="tab-content">
        <div class="form-card">
            <h2 class="form-title">Kelola Hari Libur</h2>
            <p class="form-hint" style="margin-bottom: 1.5rem;">Tambahkan hari libur nasional atau libur sekolah. Presensi akan dinonaktifkan pada hari libur.</p>

            <!-- Add Holiday Form -->
            <form action="{{ route('sekolah.libur.store') }}" method="POST" class="add-holiday-form">
                @csrf
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-input" required>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tanggal Akhir (Opsional)</label>
                    <input type="date" name="tanggal_akhir" class="form-input">
                    <small style="color: #64748b; font-size: 0.75rem;">Kosongkan untuk 1 hari</small>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Nama Libur</label>
                    <input type="text" name="nama" class="form-input" placeholder="Contoh: Libur Semester" required>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <option value="sekolah">Libur Sekolah</option>
                        <option value="nasional">Libur Nasional</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>

            <!-- Holiday List -->
            <div class="holiday-list">
                @if($liburs->count() > 0)
                    @foreach($liburs as $libur)
                        <div class="holiday-item {{ $libur->is_active ? '' : 'inactive' }}">
                            <div class="holiday-info">
                                <span class="holiday-badge {{ $libur->jenis }}">
                                    {{ $libur->jenis === 'nasional' ? 'Nasional' : 'Sekolah' }}
                                </span>
                                <div>
                                    <div class="holiday-date">{{ $libur->tanggal_range }}</div>
                                    <div class="holiday-name">
                                        {{ $libur->nama }}
                                        @if($libur->hari_count > 1)
                                            <span style="color: #667eea;">({{ $libur->hari_count }} hari)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="holiday-actions">
                                <form action="{{ route('sekolah.libur.toggle', $libur) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-toggle {{ $libur->is_active ? 'active' : 'inactive' }}">
                                        {{ $libur->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('sekolah.libur.destroy', $libur) }}" method="POST" onsubmit="return confirm('Hapus libur ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-toggle btn-delete">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    @if($liburs->hasPages())
                        <div style="margin-top: 1rem;">{{ $liburs->links() }}</div>
                    @endif
                @else
                    <div class="empty-state">
                        <p>Belum ada hari libur yang ditambahkan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Leaflet Map for Location Picker
    let map;
    let marker;
    let mapInitialized = false;

    // Default coordinates (Indonesia)
    const defaultLat = {{ $sekolah->latitude ?? '-6.200000' }};
    const defaultLng = {{ $sekolah->longitude ?? '106.816666' }};

    function initMap() {
        // Prevent re-initialization
        if (mapInitialized) {
            // Just invalidate size in case container was hidden
            if (map) map.invalidateSize();
            return;
        }

        // Check if map container exists
        const mapContainer = document.getElementById('map');
        if (!mapContainer) {
            console.log('Map container not found');
            return;
        }

        console.log('Initializing map at', defaultLat, defaultLng);

        // Initialize map with existing coordinates or default
        map = L.map('map').setView([defaultLat, defaultLng], 13);

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add marker if coordinates exist
        if (defaultLat && defaultLng) {
            marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
            marker.bindPopup('Lokasi Sekolah').openPopup();

            // Update input fields when marker is dragged
            marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat.toFixed(7);
                document.getElementById('longitude').value = position.lng.toFixed(7);
            });
        }

        // Click on map to set marker
        map.on('click', function(e) {
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng, { draggable: true }).addTo(map);

                marker.on('dragend', function(event) {
                    var position = marker.getLatLng();
                    document.getElementById('latitude').value = position.lat.toFixed(7);
                    document.getElementById('longitude').value = position.lng.toFixed(7);
                });
            }
            document.getElementById('latitude').value = e.latlng.lat.toFixed(7);
            document.getElementById('longitude').value = e.latlng.lng.toFixed(7);
            marker.bindPopup('Lokasi Sekolah').openPopup();
        });

        // Force map resize to render correctly
        setTimeout(function() {
            if (map) map.invalidateSize();
        }, 200);

        mapInitialized = true;
    }

    function switchTab(tabName) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        event.target.classList.add('active');
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('active');

        // Initialize or refresh map when Informasi tab is shown
        if (tabName === 'informasi') {
            setTimeout(function() {
                if (!mapInitialized) {
                    initMap();
                } else if (map) {
                    map.invalidateSize();
                }
            }, 100);
        }
    }

    function toggleDayFields(day, isActive) {
        const fields = ['datang_mulai', 'datang_akhir', 'pulang_mulai', 'pulang_akhir'];
        fields.forEach(field => {
            const input = document.getElementById(day + '_' + field);
            if (input) {
                input.disabled = !isActive;
                if (!isActive) {
                    input.value = '';
                }
            }
        });
    }

    // Logo preview
    document.getElementById('logoInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
                document.getElementById('logoPreview').style.display = 'block';
                const placeholder = document.getElementById('logoPlaceholder');
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    // Get current location
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Update input fields
                    document.getElementById('latitude').value = lat.toFixed(7);
                    document.getElementById('longitude').value = lng.toFixed(7);

                    // Initialize map if not already done
                    if (!mapInitialized) {
                        initMap();
                    }

                    // Update map and marker
                    map.setView([lat, lng], 15);
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    }
                    marker.bindPopup('Lokasi Sekolah').openPopup();

                    // Add drag event listener for new marker
                    marker.on('dragend', function(event) {
                        var position = marker.getLatLng();
                        document.getElementById('latitude').value = position.lat.toFixed(7);
                        document.getElementById('longitude').value = position.lng.toFixed(7);
                    });
                },
                function(error) {
                    alert('Gagal mendapatkan lokasi: ' + error.message);
                }
            );
        } else {
            alert('Browser tidak mendukung geolocation');
        }
    }

    // Clear marker
    function clearMarker() {
        if (marker && map) {
            map.removeLayer(marker);
            marker = null;
        }
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
    }

    // Initialize map when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Delay to ensure all elements are rendered
        setTimeout(initMap, 300);
    });
</script>
@endsection

