@extends('layouts.dashboard')

@section('title', 'Kelola Siswa Kelas ' . auth()->user()->kelas)

@section('styles')
<style>
    .siswa-container {
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

    /* Students Table */
    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .siswa-table {
        width: 100%;
        border-collapse: collapse;
    }

    .siswa-table th,
    .siswa-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .siswa-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #64748b;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .siswa-table tbody tr:hover {
        background: #f8fafc;
    }

    .siswa-table tbody tr:last-child td {
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
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 500;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-active .status-dot {
        background: #22c55e;
    }

    .status-inactive .status-dot {
        background: #ef4444;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }

    .btn-activate {
        background: #dcfce7;
        color: #166534;
    }

    .btn-activate:hover {
        background: #bbf7d0;
    }

    .btn-deactivate {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-deactivate:hover {
        background: #fecaca;
    }

    .btn-reset {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-reset:hover {
        background: #fde68a;
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

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .modal-text {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
    }

    .modal-buttons {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    .btn-confirm {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }

    .btn-confirm-danger {
        background: #ef4444;
        color: white;
    }

    .btn-confirm-danger:hover {
        background: #dc2626;
    }

    .btn-confirm-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-confirm-warning:hover {
        background: #d97706;
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
        .siswa-table th:nth-child(3),
        .siswa-table td:nth-child(3) {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="siswa-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Siswa Kelas {{ auth()->user()->kelas }}</h1>
            <p class="page-subtitle">Kelola akun siswa di kelas Anda</p>
        </div>
    </div>

    <!-- Info Card -->
    <div class="info-card">
        <div class="info-card-title">Kelas {{ auth()->user()->kelas }}</div>
        <div class="info-card-text">
            Total siswa: {{ count($siswaList) }} |
            Aktif: {{ count(array_filter($siswaList, fn($s) => $s['is_active'])) }} |
            Nonaktif: {{ count(array_filter($siswaList, fn($s) => !$s['is_active'])) }}
        </div>
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

    <!-- Students Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="siswa-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                            <td>{{ $siswa['kelas'] }}</td>
                            <td>
                                <span class="status-badge {{ $siswa['is_active'] ? 'status-active' : 'status-inactive' }}">
                                    <span class="status-dot"></span>
                                    {{ $siswa['is_active'] ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    @if($siswa['is_active'])
                                        <button type="button" class="btn btn-deactivate" onclick="showDeactivateModal({{ $siswa['id'] }}, '{{ $siswa['nama'] }}')">
                                            Nonaktifkan
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-activate" onclick="showActivateModal({{ $siswa['id'] }}, '{{ $siswa['nama'] }}')">
                                            Aktifkan
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-reset" onclick="showResetModal({{ $siswa['id'] }}, '{{ $siswa['nama'] }}')">
                                        Reset Password
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
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

<!-- Activate Modal -->
<div id="activateModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Aktifkan Siswa</h3>
        <p class="modal-text">Apakah Anda yakin ingin mengaktifkan akun <strong id="activateStudentName"></strong>?</p>
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeModal('activateModal')">Batal</button>
            <form id="activateForm" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-confirm btn-confirm-danger">Ya, Aktifkan</button>
            </form>
        </div>
    </div>
</div>

<!-- Deactivate Modal -->
<div id="deactivateModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Nonaktifkan Siswa</h3>
        <p class="modal-text">Apakah Anda yakin ingin menonaktifkan akun <strong id="deactivateStudentName"></strong>? Siswa tidak akan bisa login.</p>
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeModal('deactivateModal')">Batal</button>
            <form id="deactivateForm" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-confirm btn-confirm-danger">Ya, Nonaktifkan</button>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Reset Password</h3>
        <p class="modal-text">Apakah Anda yakin ingin mereset password <strong id="resetStudentName"></strong> menjadi default (12345678)?</p>
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeModal('resetModal')">Batal</button>
            <form id="resetForm" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-confirm btn-confirm-warning">Ya, Reset</button>
            </form>
        </div>
    </div>
</div>

<script>
    function showActivateModal(studentId, studentName) {
        document.getElementById('activateStudentName').textContent = studentName;
        document.getElementById('activateForm').action = '/wali-kelas/siswa/' + studentId + '/activate';
        document.getElementById('activateModal').classList.add('show');
    }

    function showDeactivateModal(studentId, studentName) {
        document.getElementById('deactivateStudentName').textContent = studentName;
        document.getElementById('deactivateForm').action = '/wali-kelas/siswa/' + studentId + '/deactivate';
        document.getElementById('deactivateModal').classList.add('show');
    }

    function showResetModal(studentId, studentName) {
        document.getElementById('resetStudentName').textContent = studentName;
        document.getElementById('resetForm').action = '/wali-kelas/siswa/' + studentId + '/reset-password';
        document.getElementById('resetModal').classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    }
</script>
@endsection

