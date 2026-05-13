@extends('layouts.dashboard')

@section('title', 'Kelola Siswa')

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

    /* Header Actions */
    .header-actions {
        display: flex;
        gap: 0.75rem;
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

    .import-errors {
        margin-top: 0.5rem;
        padding-left: 1.5rem;
        font-size: 0.8125rem;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .filter-form {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        max-width: 300px;
    }

    .filter-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-select {
        width: 100%;
        padding: 0.625rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.9375rem;
        background-color: white;
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

    /* Action Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-outline {
        background: white;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }

    .btn-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .btn-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-danger:hover {
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
        max-width: 500px;
        width: 90%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 1rem;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #64748b;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.625rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9375rem;
    }

    .help-text {
        font-size: 0.8125rem;
        color: #64748b;
        margin-top: 0.5rem;
    }

    .template-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .template-link:hover {
        text-decoration: underline;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 2rem;
    }

    /* Badges */
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-nis {
        background: #e0e7ff;
        color: #4338ca;
    }

    .badge-kelas {
        background: #dcfce7;
        color: #166534;
    }
</style>
@endsection

@section('content')
<div class="siswa-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Siswa</h1>
            <p class="page-subtitle">Master data seluruh siswa di sistem</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="openImportModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Impor Siswa
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            @if(session('import_errors'))
                <ul class="import-errors">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="filter-form">
            <div class="filter-group">
                <label for="kelas">Filter Kelas</label>
                <select name="kelas" id="kelas" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($availableClasses as $kelas)
                        <option value="{{ $kelas }}" {{ $kelasDipilih == $kelas ? 'selected' : '' }}>
                            {{ $kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-outline">
                Filter
            </button>
        </form>
    </div>

    <!-- Students Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="siswa-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaList as $index => $siswa)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge badge-nis">{{ $siswa->nis }}</span></td>
                            <td><strong>{{ $siswa->user->name ?? $siswa->nama }}</strong></td>
                            <td><span class="badge badge-kelas">{{ $siswa->kelas }}</span></td>
                            <td>
                                <button type="button" class="btn btn-danger" onclick="showDeleteModal({{ $siswa->user_id }}, '{{ $siswa->user->name ?? $siswa->nama }}')">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem; color: #64748b;">
                                Belum ada data siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Impor Data Siswa</h3>
            <button class="close-modal" onclick="closeModal('importModal')">&times;</button>
        </div>
        <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>1. Download Template</label>
                <p class="help-text">
                    Gunakan file CSV berikut sebagai format data: 
                    <a href="{{ route('admin.siswa.template') }}" class="template-link">Download Template CSV</a>
                </p>
            </div>
            <div class="form-group">
                <label for="file">2. Pilih File CSV</label>
                <input type="file" name="file" id="file" class="form-control" accept=".csv" required>
                <p class="help-text">Pastikan file dalam format .csv dan mengikuti template.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('importModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Mulai Impor</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Hapus Siswa</h3>
            <button class="close-modal" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <p style="color: #64748b; margin-bottom: 1.5rem;">Apakah Anda yakin ingin menghapus data siswa <strong id="deleteSiswaName"></strong>? Akun user juga akan terhapus.</p>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('deleteModal')">Batal</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="background: #ef4444; color: white;">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openImportModal() {
        document.getElementById('importModal').classList.add('show');
    }

    function showDeleteModal(userId, name) {
        document.getElementById('deleteSiswaName').textContent = name;
        document.getElementById('deleteForm').action = '/admin/siswa/' + userId + '/delete';
        document.getElementById('deleteModal').classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    }
</script>
@endsection
