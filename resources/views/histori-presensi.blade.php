@extends('layouts.dashboard')

@section('title', 'Histori Presensi')

@section('styles')
<style>
    .histori-container {
        padding: 1.5rem;
        max-width: 1600px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 0.9375rem;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .filter-form {
        display: flex;
        align-items: flex-end;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label {
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
    }

    .filter-input, .filter-select {
        padding: 0.625rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9375rem;
        color: #1e293b;
        width: 180px;
        height: 42px;
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-filter {
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Table */
    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .histori-table {
        width: 100%;
        border-collapse: collapse;
    }

    .histori-table th,
    .histori-table td {
        padding: 0.875rem 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .histori-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #64748b;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .histori-table tbody tr:hover {
        background: #f8fafc;
    }

    .histori-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Student Info */
    .student-info {
        display: flex;
        flex-direction: column;
    }

    .student-name {
        font-weight: 600;
        color: #1e293b;
    }

    .student-nis {
        font-size: 0.8125rem;
        color: #64748b;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .status-hadir {
        background: #dcfce7;
        color: #166534;
    }

    .status-izin {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-sakit {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-alfa {
        background: #f1f5f9;
        color: #475569;
    }

    /* Percentage Bar */
    .percentage-bar {
        width: 100%;
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }

    .percentage-fill {
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    /* Empty State */
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

    /* Info Card */
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .info-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .info-card-text {
        opacity: 0.9;
        font-size: 0.9375rem;
    }

    /* Action Button */
    .btn-detail {
        padding: 0.375rem 0.75rem;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-detail:hover {
        background: #5568d3;
    }

    .btn-cetak-inline {
        padding: 0.375rem 0.75rem;
        background: #10b981;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-cetak-inline:hover {
        background: #0ea57f;
    }

    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-input, .filter-select {
            width: 100%;
        }

        .btn-filter {
            width: 100%;
            justify-content: center;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="histori-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Histori Presensi</h1>
            <p class="page-subtitle">Riwayat kehadiran siswa dalam periode tertentu</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('histori-presensi.index') }}" class="filter-form">
            <div class="filter-group">
                <label for="tanggal_mulai" class="filter-label">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="filter-input" value="{{ $tanggalMulai }}">
            </div>

            <div class="filter-group">
                <label for="tanggal_akhir" class="filter-label">Tanggal Akhir</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="filter-input" value="{{ $tanggalAkhir }}">
            </div>

            @if(auth()->user()->isSuperAdmin() || auth()->user()->isBK())
            <div class="filter-group">
                <label for="kelas" class="filter-label">Kelas</label>
                <select id="kelas" name="kelas" class="filter-select">
                    <option value="">Semua Kelas</option>
                    @foreach($availableClasses as $kelas)
                        <option value="{{ $kelas }}" {{ $kelasDipilih == $kelas ? 'selected' : '' }}>
                            {{ $kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="filter-group">
                <label for="kelas" class="filter-label">Kelas</label>
                <input type="text" id="kelas" class="filter-input" value="{{ auth()->user()->kelas }}" readonly>
            </div>
            @endif

            <div class="filter-group">
                <button type="submit" class="btn-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="info-card">
        <div class="info-card-title">
            @if($kelasDipilih)
                Histori Presensi Kelas {{ $kelasDipilih }}
            @else
                Histori Presensi Semua Kelas
            @endif
        </div>
        <div class="info-card-text">
            Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d F Y') }}
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="histori-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alfa</th>
                        <th>Persentase</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="student-info">
                                    <span class="student-name">{{ $data['nama'] }}</span>
                                </div>
                            </td>
                            <td>{{ $data['nis'] }}</td>
                            <td>{{ $data['kelas'] }}</td>
                            <td>
                                <span class="status-badge status-hadir">{{ $data['hadir'] }}</span>
                            </td>
                            <td>
                                <span class="status-badge status-izin">{{ $data['izin'] }}</span>
                            </td>
                            <td>
                                <span class="status-badge status-sakit">{{ $data['sakit'] }}</span>
                            </td>
                            <td>
                                <span class="status-badge status-alfa">{{ $data['alfa'] }}</span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div class="percentage-bar" style="width: 80px;">
                                        <div class="percentage-fill" style="width: {{ $data['persentase_hadir'] }}%;"></div>
                                    </div>
                                    <span style="font-size: 0.8125rem; color: #64748b;">{{ $data['persentase_hadir'] }}%</span>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('histori-presensi.detail', ['siswa' => $data['user_id'], 'tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir]) }}" class="btn-detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>
                                <a href="{{ route('histori-presensi.cetak', ['siswa' => $data['user_id'], 'tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir]) }}" target="_blank" class="btn-cetak-inline" style="margin-left:0.5rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 22h12v-7M6 15h12" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>Tidak ada data siswa dalam periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

