@extends('layouts.dashboard')

@section('title', 'Rekap Presensi')

@section('styles')
<style>
    .rekap-container {
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

    .btn-cetak {
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-cetak:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.25rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .stat-card-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: #64748b;
    }

    .stat-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-card-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
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

    .rekap-table {
        width: 100%;
        border-collapse: collapse;
    }

    .rekap-table th,
    .rekap-table td {
        padding: 0.875rem 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .rekap-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #64748b;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .rekap-table tbody tr:hover {
        background: #f8fafc;
    }

    .rekap-table tbody tr:last-child td {
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

    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-input, .filter-select {
            width: 100%;
        }

        .btn-filter, .btn-cetak {
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
<div class="rekap-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Rekap Presensi</h1>
            <p class="page-subtitle">Laporan rekapitulasi kehadiran siswa dalam periode tertentu</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('rekap-presensi.index') }}" class="filter-form">
            <div class="filter-group">
                <label for="tanggal_mulai" class="filter-label">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="filter-input" value="{{ $tanggalMulai }}">
            </div>

            <div class="filter-group">
                <label for="tanggal_akhir" class="filter-label">Tanggal Akhir</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="filter-input" value="{{ $tanggalAkhir }}">
            </div>

            @if(auth()->user()->isSuperAdmin())
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

            <div class="filter-group" style="flex-direction: row; gap: 0.5rem;">
                <button type="submit" class="btn-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('rekap-presensi.cetak', ['tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir, 'kelas' => $kelasDipilih]) }}" target="_blank" class="btn-cetak">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="info-card">
        <div class="info-card-title">
            @if($kelasDipilih)
                Rekap Presensi Kelas {{ $kelasDipilih }}
            @else
                Rekap Presensi Semua Kelas
            @endif
        </div>
        <div class="info-card-text">
            Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d F Y') }}
            <br>
            Total Hari: {{ count($dateRange) }} hari
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Siswa</span>
                <div class="stat-card-icon" style="background: #dbeafe;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#1e40af" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ count($rekapData) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Hadir</span>
                <div class="stat-card-icon" style="background: #dcfce7;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#166534" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value" style="color: #166534;">{{ $overallStats['total_hadir'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Izin</span>
                <div class="stat-card-icon" style="background: #dbeafe;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#1e40af" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value" style="color: #1e40af;">{{ $overallStats['total_izin'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Sakit</span>
                <div class="stat-card-icon" style="background: #fee2e2;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#991b1b" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value" style="color: #991b1b;">{{ $overallStats['total_sakit'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Alfa</span>
                <div class="stat-card-icon" style="background: #f1f5f9;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#475569" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value" style="color: #475569;">{{ $overallStats['total_alfa'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Persentase Hadir</span>
                <div class="stat-card-icon" style="background: #fef3c7;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#b45309" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value" style="color: #b45309;">{{ $overallStats['persentase_hadir'] }}%</div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="rekap-table">
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapData as $index => $data)
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>Tidak ada data presensi dalam periode ini.</p>
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

