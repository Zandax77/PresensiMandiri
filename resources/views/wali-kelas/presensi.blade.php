@extends('layouts.dashboard')

@section('title', 'Presensi Kelas ' . auth()->user()->kelas)

@section('styles')
<style>
    .presensi-container {
        padding: 1.5rem;
        max-width: 1400px;
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

    /* Date Filter */
    .date-filter {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .date-filter-form {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .date-filter label {
        font-weight: 500;
        color: #374151;
    }

    .date-input {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9375rem;
        color: #1e293b;
    }

    .date-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-filter {
        padding: 0.5rem 1.25rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-filter-secondary {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
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

    .presensi-table {
        width: 100%;
        border-collapse: collapse;
    }

    .presensi-table th,
    .presensi-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .presensi-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #64748b;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .presensi-table tbody tr:hover {
        background: #f8fafc;
    }

    .presensi-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Student Info */
    .student-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
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
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: capitalize;
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

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        margin-right: 0.375rem;
    }

    .status-hadir .status-dot { background: #22c55e; }
    .status-izin .status-dot { background: #3b82f6; }
    .status-sakit .status-dot { background: #ef4444; }
    .status-alfa .status-dot { background: #64748b; }

    /* Time Info */
    .time-info {
        font-size: 0.875rem;
        color: #64748b;
    }

    .time-label {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* Keterangan */
    .keterangan-text {
        font-size: 0.8125rem;
        color: #64748b;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
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
        .presensi-table th:nth-child(5),
        .presensi-table td:nth-child(5),
        .presensi-table th:nth-child(6),
        .presensi-table td:nth-child(6) {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="presensi-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Presensi Kelas {{ auth()->user()->kelas }}</h1>
            <p class="page-subtitle">Detail presensi siswa - {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
        </div>
    </div>

    <!-- Info Card -->
    <div class="info-card">
        <div class="info-card-title">Kelas {{ auth()->user()->kelas }} - {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</div>
        <div class="info-card-text">
            Total Siswa: {{ $stats['total'] }} |
            Hadir: {{ $stats['hadir'] }} ({{ $stats['persentase_hadir'] }}%) |
            Izin: {{ $stats['izin'] }} |
            Sakit: {{ $stats['sakit'] }} |
            Alfa: {{ $stats['alfa'] }}
        </div>
    </div>

    <!-- Date Filter -->
    <div class="date-filter">
        <form method="GET" action="{{ route('wali-kelas.presensi') }}" class="date-filter-form">
            <label for="tanggal">Pilih Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" class="date-input" value="{{ $tanggal }}">
            <button type="submit" class="btn-filter">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16" style="display: inline; margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                Filter
            </button>
            <a href="{{ route('wali-kelas.presensi') }}" class="btn-filter btn-filter-secondary">
                Hari Ini
            </a>
        </form>
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
            <div class="stat-card-value">{{ $stats['total'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Hadir</span>
                <div class="stat-card-icon" style="background: #dcfce7;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#166534" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value" style="color: #166534;">{{ $stats['hadir'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Izin</span>
                <div class="stat-card-icon" style="background: #dbeafe;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#1e40af" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value" style="color: #1e40af;">{{ $stats['izin'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Sakit</span>
                <div class="stat-card-icon" style="background: #fee2e2;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#991b1b" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value" style="color: #991b1b;">{{ $stats['sakit'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Alfa</span>
                <div class="stat-card-icon" style="background: #f1f5f9;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#475569" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value" style="color: #475569;">{{ $stats['alfa'] }}</div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="presensi-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Status</th>
                        <th>Jam Datang</th>
                        <th>Jam Pulang</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaList as $index => $siswa)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar">
                                        {{ strtoupper(substr($siswa['nama'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="student-name">{{ $siswa['nama'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $siswa['nis'] }}</td>
                            <td>
                                <span class="status-badge status-{{ $siswa['status'] }}">
                                    <span class="status-dot"></span>
                                    {{ ucfirst($siswa['status']) }}
                                </span>
                            </td>
                            <td>
                                <div class="time-info">
                                    @if($siswa['jam_datang'])
                                        {{ $siswa['jam_datang'] }}
                                    @else
                                        <span style="color: #cbd5e1;">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="time-info">
                                    @if($siswa['jam_pulang'])
                                        {{ $siswa['jam_pulang'] }}
                                    @else
                                        <span style="color: #cbd5e1;">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="keterangan-text" title="{{ $siswa['keterangan'] ?? '-' }}">
                                    {{ $siswa['keterangan'] ?? '-' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>Belum ada siswa di kelas ini.</p>
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

