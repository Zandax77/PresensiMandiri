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
        margin-top: 1rem;
    }

    .holiday-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        border: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .holiday-item.inactive {
        opacity: 0.6;
        background: #f1f5f9;
    }

    .holiday-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .holiday-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .holiday-badge.nasional {
        background: #fee2e2;
        color: #991b1b;
    }

    .holiday-badge.sekolah {
        background: #dbeafe;
        color: #1e40af;
    }

    .holiday-date {
        font-weight: 600;
        color: #1e293b;
    }

    .holiday-name {
        color: #64748b;
        font-size: 0.875rem;
    }

    .holiday-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-toggle {
        padding: 0.5rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-toggle.active {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-toggle.inactive {
        background: #dcfce7;
        color: #166534;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    /* Add Holiday Form */
    .add-holiday-form {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .add-holiday-form {
            grid-template-columns: 1fr;
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
    function switchTab(tabName) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        event.target.classList.add('active');
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('active');
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
</script>
@endsection

