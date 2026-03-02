@extends('layouts.dashboard')

@section('title', 'Kelola Pengajuan Ijin')

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

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.25rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-card.active {
        border-color: #667eea;
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
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

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }

    .btn-approve {
        background: #10b981;
        color: white;
    }

    .btn-approve:hover {
        background: #059669;
    }

    .btn-reject {
        background: #ef4444;
        color: white;
    }

    .btn-reject:hover {
        background: #dc2626;
    }

    .btn-view {
        background: #64748b;
        color: white;
    }

    .btn-view:hover {
        background: #475569;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 2rem;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
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
    .form-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9375rem;
        color: #1e293b;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-cancel:hover {
        background: #cbd5e1;
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

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="izin-container">
    <div class="izin-header">
        <h1 class="izin-title">Kelola Pengajuan Ijin</h1>
        <p class="izin-subtitle">Terima atau tolak pengajuan ijin dari siswa</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <a href="{{ route('izin.index', ['status' => 'menunggu']) }}" class="stat-card {{ $status === 'menunggu' ? 'active' : '' }}">
            <div class="stat-card-header">
                <span class="stat-card-title">Menunggu</span>
                <div class="stat-card-icon" style="background: #fef3c7;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#92400e" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['menunggu'] }}</div>
        </a>

        <a href="{{ route('izin.index', ['status' => 'diterima']) }}" class="stat-card {{ $status === 'diterima' ? 'active' : '' }}">
            <div class="stat-card-header">
                <span class="stat-card-title">Diterima</span>
                <div class="stat-card-icon" style="background: #dcfce7;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#166534" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['diterima'] }}</div>
        </a>

        <a href="{{ route('izin.index', ['status' => 'ditolak']) }}" class="stat-card {{ $status === 'ditolak' ? 'active' : '' }}">
            <div class="stat-card-header">
                <span class="stat-card-title">Ditolak</span>
                <div class="stat-card-icon" style="background: #fee2e2;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#991b1b" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['ditolak'] }}</div>
        </a>

        <a href="{{ route('izin.index', ['status' => 'semua']) }}" class="stat-card {{ $status === 'semua' ? 'active' : '' }}">
            <div class="stat-card-header">
                <span class="stat-card-title">Semua</span>
                <div class="stat-card-icon" style="background: #e2e8f0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#475569" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $stats['menunggu'] + $stats['diterima'] + $stats['ditolak'] }}</div>
        </a>
    </div>

    <!-- Table -->
    <div class="table-card">
        <h3 class="table-card-title">Daftar Pengajuan Ijin - {{ ucfirst($status) }}</h3>

        @if($pengajuanIjin->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>Durasi</th>
                    <th>Alasan</th>
                    <th>Berkas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuanIjin as $izin)
                <tr>
                    <td><strong>{{ $izin->user->name }}</strong></td>
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
                        <div class="action-buttons">
                            @if($izin->status === 'menunggu')
                            <button class="btn btn-approve" onclick="openModal('approve', {{ $izin->id }})">
                                Terima
                            </button>
                            <button class="btn btn-reject" onclick="openModal('reject', {{ $izin->id }})">
                                Tolak
                            </button>
                            @else
                            <button class="btn btn-view" onclick="viewDetails({{ $izin->id }})">
                                Detail
                            </button>
                            @endif
                        </div>
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
            <p>Tidak ada pengajuan ijin dengan status "{{ $status }}"</p>
        </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="modal">
<div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Terima Pengajuan Ijin</h3>
            <button class="modal-close" onclick="closeModal('approve')">&times;</button>
        </div>
        <form id="approveForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea name="catatan" class="form-textarea" placeholder="Tambahkan catatan..."></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('approve')">Batal</button>
                <button type="submit" class="btn btn-approve">Terima</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Tolak Pengajuan Ijin</h3>
            <button class="modal-close" onclick="closeModal('reject')">&times;</button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Alasan Penolakan <span style="color: red;">*</span></label>
                <textarea name="catatan" class="form-textarea" placeholder="Jelaskan alasan penolakan..." required minlength="5"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('reject')">Batal</button>
                <button type="submit" class="btn btn-reject">Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(type, id) {
        const modal = document.getElementById(type + 'Modal');
        const form = document.getElementById(type + 'Form');

        if (type === 'approve') {
            form.action = '/izin/' + id + '/approve';
        } else {
            form.action = '/izin/' + id + '/reject';
        }

        modal.style.display = 'block';
    }

    function closeModal(type) {
        const modal = document.getElementById(type + 'Modal');
        modal.style.display = 'none';
    }

    function viewDetails(id) {
        // For now, just show an alert with the details
        alert('Lihat detail pengajuan #' + id);
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
</script>
@endsection

