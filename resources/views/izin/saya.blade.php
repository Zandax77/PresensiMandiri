@extends('layouts.dashboard')

@section('title', 'Pengajuan Ijin Saya')

@section('styles')
<style>
    .izin-container {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .izin-header {
        margin-bottom: 2rem;
    }

    .izin-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .izin-subtitle {
        color: #64748b;
        font-size: 0.9375rem;
    }

    /* Form Card */
    .form-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .form-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9375rem;
        color: #1e293b;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-hint {
        font-size: 0.8125rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Table Card */
    .table-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    .table-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th,
    .data-table td {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table th {
        font-weight: 600;
        color: #64748b;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: #f8fafc;
    }

    .data-table td {
        font-size: 0.9375rem;
        color: #1e293b;
    }

    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 500;
    }

    .status-menunggu {
        background: #fef3c7;
        color: #92400e;
    }

    .status-diterima {
        background: #dcfce7;
        color: #166534;
    }

    .status-ditolak {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-izin {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-sakit {
        background: #fce7f3;
        color: #9d174d;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #64748b;
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin-bottom: 1rem;
        color: #cbd5e1;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
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

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        color: #64748b;
        background: #f1f5f9;
        transition: all 0.2s;
    }

    .filter-tab:hover {
        background: #e2e8f0;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="izin-container">
    <div class="izin-header">
        <h1 class="izin-title">Pengajuan Ijin Saya</h1>
        <p class="izin-subtitle">Kirim dan lihat status pengajuan ijin Anda</p>
    </div>

    <!-- Alert Messages -->
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

    <!-- Form Card -->
    <div class="form-card">
        <h3 class="form-card-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Ajukan Ijin Baru
        </h3>

        <form method="POST" action="{{ route('izin.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jenis Ijin <span style="color: red;">*</span></label>
                    <select name="jenis_izin" class="form-select" required>
                        <option value="">Pilih Jenis Ijin</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Awal <span style="color: red;">*</span></label>
                    <input type="date" name="tanggal_awal" class="form-input" required min="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Akhir <span style="color: red;">*</span></label>
                    <input type="date" name="tanggal_akhir" class="form-input" required min="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alasan <span style="color: red;">*</span></label>
                <textarea name="alasan" class="form-textarea" placeholder="Jelaskan alasan pengajuan ijin..." required minlength="10"></textarea>
                <p class="form-hint">Minimal 10 karakter</p>
            </div>

            <div class="form-group">
                <label class="form-label">Berkas Pendukung (Opsional)</label>
                <input type="file" name="berkas" class="form-input" accept=".jpg,.jpeg,.pdf">
                <p class="form-hint">Format: JPG, JPEG, atau PDF. Maksimal ukuran: 1 MB</p>
            </div>

            <button type="submit" class="btn-submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="18" height="18" style="margin-right: 0.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                Kirim Pengajuan
            </button>
        </form>
    </div>

    <!-- History Card -->
    <div class="table-card">
        <h3 class="table-card-title">Riwayat Pengajuan</h3>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="{{ route('izin.saya', ['status' => 'semua']) }}" class="filter-tab {{ $status === 'semua' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('izin.saya', ['status' => 'menunggu']) }}" class="filter-tab {{ $status === 'menunggu' ? 'active' : '' }}">Menunggu</a>
            <a href="{{ route('izin.saya', ['status' => 'diterima']) }}" class="filter-tab {{ $status === 'diterima' ? 'active' : '' }}">Diterima</a>
            <a href="{{ route('izin.saya', ['status' => 'ditolak']) }}" class="filter-tab {{ $status === 'ditolak' ? 'active' : '' }}">Ditolak</a>
        </div>

        @if($pengajuanIjin->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>Durasi</th>
                    <th>Alasan</th>
                    <th>Berkas</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuanIjin as $index => $izin)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span class="status-badge status-{{ $izin->jenis_izin }}">
                            {{ $izin->jenis_izin === 'izin' ? 'Izin' : 'Sakit' }}
                        </span>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($izin->tanggal_awal)->format('d/m/Y') }}
                        @if($izin->tanggal_awal !== $izin->tanggal_akhir)
                            - {{ \Carbon\Carbon::parse($izin->tanggal_akhir)->format('d/m/Y') }}
                        @endif
                    </td>
                    <td>{{ $izin->durasi }} hari</td>
                    <td>
                        <span style="max-width: 200px; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $izin->alasan }}
                        </span>
                    </td>
                    <td>
                        @if($izin->berkas)
                            <a href="{{ route('izin.berkas', $izin->id) }}" target="_blank" class="btn btn-view" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                Lihat
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-{{ $izin->status }}">
                            {{ ucfirst($izin->status) }}
                        </span>
                    </td>
                    <td>
                        @if($izin->catatan)
                            <span title="{{ $izin->catatan }}" style="cursor: pointer;">
                                {{ \Illuminate\Support\Str::limit($izin->catatan, 30) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p>Belum ada pengajuan ijin</p>
        </div>
        @endif
    </div>
</div>
@endsection

