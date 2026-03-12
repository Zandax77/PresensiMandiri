@extends('layouts.dashboard')

@section('title', 'Detail Histori Presensi')

@section('styles')
<style>
    .detail-container {
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

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-cetak {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-cetak:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102,126,234,0.3);
    }

    .btn-back:hover {
        background: #e2e8f0;
        color: #1e293b;
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

    .filter-input {
        padding: 0.625rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9375rem;
        color: #1e293b;
        width: 180px;
        height: 42px;
    }

    .filter-input:focus {
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

    /* Student Info Card */
    .student-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .student-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .student-details {
        flex: 1;
    }

    .student-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .student-meta {
        display: flex;
        gap: 1.5rem;
        color: #64748b;
        font-size: 0.9375rem;
    }

    .student-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.25rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-card-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-card-label {
        font-size: 0.875rem;
        color: #64748b;
    }

    .stat-hadir .stat-card-value { color: #166534; }
    .stat-izin .stat-card-value { color: #1e40af; }
    .stat-sakit .stat-card-value { color: #991b1b; }
    .stat-alfa .stat-card-value { color: #475569; }
    .stat-persentase .stat-card-value { color: #667eea; }

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

    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }

    .detail-table th,
    .detail-table td {
        padding: 0.875rem 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .detail-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #64748b;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .detail-table tbody tr:hover {
        background: #f8fafc;
    }

    .detail-table tbody tr:last-child td {
        border-bottom: none;
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

    /* Time Display */
    .time-display {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .time-label {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .time-value {
        font-weight: 600;
        color: #1e293b;
    }

    .time-empty {
        font-size: 0.8125rem;
        color: #cbd5e1;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #64748b;
    }

    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-input {
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

        .student-meta {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="detail-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Histori Presensi</h1>
            <p class="page-subtitle">Riwayat kehadiran {{ $siswaName }}</p>
        </div>
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            <a href="{{ route('histori-presensi.index', ['tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir]) }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('histori-presensi.cetak', ['siswa' => $siswaData->user_id, 'tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir]) }}" target="_blank" class="btn-cetak">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16" style="margin-right:4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 22h12v-7M6 15h12" />
                </svg>
                Cetak
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('histori-presensi.detail', $siswaData->user_id) }}" class="filter-form">
            <div class="filter-group">
                <label for="tanggal_mulai" class="filter-label">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="filter-input" value="{{ $tanggalMulai }}">
            </div>

            <div class="filter-group">
                <label for="tanggal_akhir" class="filter-label">Tanggal Akhir</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="filter-input" value="{{ $tanggalAkhir }}">
            </div>

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

    <!-- Student Info Card -->
    <div class="student-card">
        <div class="student-info">
            <div class="student-avatar">
                {{ strtoupper(substr($siswaName, 0, 1)) }}
            </div>
            <div class="student-details">
                <div class="student-name">{{ $siswaName }}</div>
                <div class="student-meta">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        NIS: {{ $siswaData->nis ?? 'N/A' }}
                    </span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Kelas: {{ $siswaData->kelas ?? 'N/A' }}
                    </span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-hadir">
            <div class="stat-card-value">{{ $stats['hadir'] }}</div>
            <div class="stat-card-label">Hadir</div>
        </div>
        <div class="stat-card stat-izin">
            <div class="stat-card-value">{{ $stats['izin'] }}</div>
            <div class="stat-card-label">Izin</div>
        </div>
        <div class="stat-card stat-sakit">
            <div class="stat-card-value">{{ $stats['sakit'] }}</div>
            <div class="stat-card-label">Sakit</div>
        </div>
        <div class="stat-card stat-alfa">
            <div class="stat-card-value">{{ $stats['alfa'] }}</div>
            <div class="stat-card-label">Alfa</div>
        </div>
        <div class="stat-card stat-persentase">
            <div class="stat-card-value">{{ $stats['persentase_hadir'] }}%</div>
            <div class="stat-card-label">Persentase Hadir</div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Status</th>
                        <th>Jam Datang</th>
                        <th>Jam Pulang</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historiData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data['tanggal_formatted'] }}</td>
                            <td>{{ $data['hari'] }}</td>
                            <td>
                                <span class="status-badge status-{{ $data['status'] }}">
                                    {{ ucfirst($data['status']) }}
                                </span>
                            </td>
                            <td>
                                @if($data['jam_datang'])
                                    <div class="time-display">
                                        <span class="time-value">{{ $data['jam_datang'] }}</span>
                                    </div>
                                @else
                                    <span class="time-empty">-</span>
                                @endif
                            </td>
                            <td>
                                @if($data['jam_pulang'])
                                    <div class="time-display">
                                        <span class="time-value">{{ $data['jam_pulang'] }}</span>
                                    </div>
                                @else
                                    <span class="time-empty">-</span>
                                @endif
                            </td>
                            <td>
                                @if($data['keterangan'])
                                    <span style="font-size: 0.8125rem; color: #64748b;">{{ $data['keterangan'] }}</span>
                                @else
                                    <span class="time-empty">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
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

