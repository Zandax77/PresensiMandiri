@extends('layouts.dashboard')

@section('title', 'Kelola User')

@section('styles')
<style>
    .users-container {
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

    /* Users Table */
    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th,
    .users-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .users-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #64748b;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .users-table tbody tr:hover {
        background: #f8fafc;
    }

    .users-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* User Info */
    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
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

    .user-name {
        font-weight: 600;
        color: #1e293b;
    }

    .user-email {
        font-size: 0.8125rem;
        color: #64748b;
    }

    /* Role Badge */
    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 500;
    }

    .role-siswa {
        background: #dbeafe;
        color: #1e40af;
    }

    .role-wali_kelas {
        background: #fce7f3;
        color: #9d174d;
    }

    .role-bk {
        background: #fef3c7;
        color: #92400e;
    }

    .role-kesiswaan {
        background: #d1fae5;
        color: #065f46;
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

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #fecaca;
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

    @media (max-width: 768px) {
        .users-table th:nth-child(4),
        .users-table td:nth-child(4) {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="users-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola User</h1>
            <p class="page-subtitle">Kelola akun pengguna sistem presensi</p>
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

    <!-- Users Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email / NIS</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-email">Bergabung: {{ $user->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge role-{{ $user->role }}">
                                    {{ $user->getRoleLabelAttribute() }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $user->isActive() ? 'status-active' : 'status-inactive' }}">
                                    <span class="status-dot"></span>
                                    {{ $user->isActive() ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    @if($user->isActive())
                                        @if($user->id !== auth()->user()->id)
                                            <button type="button" class="btn btn-deactivate" onclick="showDeactivateModal({{ $user->id }}, '{{ $user->name }}')">
                                                Nonaktifkan
                                            </button>
                                        @endif
                                    @else
                                        <button type="button" class="btn btn-activate" onclick="showActivateModal({{ $user->id }}, '{{ $user->name }}')">
                                            Aktifkan
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-reset" onclick="showResetModal({{ $user->id }}, '{{ $user->name }}')">
                                        Reset Password
                                    </button>
                                    @if($user->id !== auth()->user()->id)
                                        <button type="button" class="btn btn-delete" onclick="showDeleteModal({{ $user->id }}, '{{ $user->name }}')">
                                            Hapus
                                        </button>
                                    @endif
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
                                    <p>Belum ada user terdaftar.</p>
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
        <h3 class="modal-title">Aktifkan User</h3>
        <p class="modal-text">Apakah Anda yakin ingin mengaktifkan akun <strong id="activateUserName"></strong>?</p>
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
        <h3 class="modal-title">Nonaktifkan User</h3>
        <p class="modal-text">Apakah Anda yakin ingin menonaktifkan akun <strong id="deactivateUserName"></strong>? User tidak akan bisa login.</p>
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
        <p class="modal-text">Apakah Anda yakin ingin mereset password <strong id="resetUserName"></strong> menjadi default (12345678)?</p>
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeModal('resetModal')">Batal</button>
            <form id="resetForm" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-confirm btn-confirm-warning">Ya, Reset</button>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Hapus User</h3>
        <p class="modal-text">Apakah Anda yakin ingin menghapus akun <strong id="deleteUserName"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">Batal</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-confirm btn-confirm-danger">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
    function showActivateModal(userId, userName) {
        document.getElementById('activateUserName').textContent = userName;
        document.getElementById('activateForm').action = '/users/' + userId + '/activate';
        document.getElementById('activateModal').classList.add('show');
    }

    function showDeactivateModal(userId, userName) {
        document.getElementById('deactivateUserName').textContent = userName;
        document.getElementById('deactivateForm').action = '/users/' + userId + '/deactivate';
        document.getElementById('deactivateModal').classList.add('show');
    }

    function showResetModal(userId, userName) {
        document.getElementById('resetUserName').textContent = userName;
        document.getElementById('resetForm').action = '/users/' + userId + '/reset-password';
        document.getElementById('resetModal').classList.add('show');
    }

    function showDeleteModal(userId, userName) {
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteForm').action = '/users/' + userId + '/delete';
        document.getElementById('deleteModal').classList.add('show');
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

