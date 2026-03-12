@extends('layouts.dashboard')

@section('title', 'Generate QR Code Siswa')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Generate QR Code Siswa</h1>
        <p class="page-subtitle">Pilih siswa untuk menghasilkan kartu ID dengan QR Code</p>
    </div>

    <!-- Filter Form -->
    <div class="filter-card">
        <form method="GET" action="{{ route('qr-code.index') }}" class="filter-form">
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
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
        </form>
    </div>

    <!-- Success/Error Messages -->
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
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Status Akun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaList as $index => $siswa)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="student-photo">
                                @if($siswa->foto)
                                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto {{ $siswa->nama }}" class="photo-thumb">
                                @else
                                    <div class="photo-placeholder-small">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                    </div>
                                @endif
                                <button class="btn-upload-photo" onclick="openPhotoModal('{{ $siswa->nis }}', '{{ $siswa->nama }}', '{{ $siswa->foto ?? '' }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span class="nis-badge">{{ $siswa->nis }}</span>
                        </td>
                        <td>{{ $siswa->nama }}</td>
                        <td>
                            <span class="kelas-badge">{{ $siswa->kelas }}</span>
                        </td>
                        <td>
                            @if($siswa->user && $siswa->user->is_active)
                                <span class="status-badge status-active">Aktif</span>
                            @else
                                <span class="status-badge status-inactive">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('qr-code.generate', $siswa->nis) }}" class="btn btn-success btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                Generate QR
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-content">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" width="48" height="48">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                </svg>
                                <p>Tidak ada data siswa</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Photo Upload Modal -->
<div id="photoModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Upload Foto Siswa</h3>
            <button class="modal-close" onclick="closePhotoModal()">&times;</button>
        </div>
        <form id="photoForm" method="POST" action="{{ route('qr-code.upload-photo') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <p id="modalStudentName" class="student-name"></p>

                <div class="photo-preview-container">
                    <div class="photo-preview" id="photoPreview">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                </div>

                <input type="hidden" name="nis" id="modalNis">

                <div class="form-group">
                    <label for="foto">Pilih Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*" required>
                    <p class="help-text">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Upload Foto</button>
            </div>
        </form>

        <form id="deletePhotoForm" method="POST" action="{{ route('qr-code.delete-photo') }}" style="display: none;">
            @csrf
            <input type="hidden" name="nis" id="deleteNis">
        </form>
    </div>
</div>

<style>
    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 0.9375rem;
    }

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
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f8fafc;
        padding: 1rem;
        text-align: left;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9375rem;
        color: #334155;
    }

    .data-table tbody tr:hover {
        background: #f8fafc;
    }

    .student-photo {
        position: relative;
        width: 50px;
        height: 50px;
    }

    .photo-thumb {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
    }

    .photo-placeholder-small {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }

    .photo-placeholder-small svg {
        width: 24px;
        height: 24px;
    }

    .btn-upload-photo {
        position: absolute;
        bottom: -5px;
        right: -5px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #667eea;
        color: white;
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-upload-photo:hover {
        background: #5568d3;
    }

    .nis-badge {
        background: #e0e7ff;
        color: #4338ca;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        font-family: monospace;
    }

    .kelas-badge {
        background: #dcfce7;
        color: #166534;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8125rem;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .empty-state {
        text-align: center;
        padding: 3rem !important;
    }

    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .empty-content svg {
        color: #cbd5e1;
    }

    .empty-content p {
        color: #94a3b8;
        font-size: 0.9375rem;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.2s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        margin: 10% auto;
        padding: 0;
        border-radius: 12px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: slideDown 0.3s;
    }

    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #64748b;
        cursor: pointer;
    }

    .modal-close:hover {
        color: #1e293b;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .student-name {
        font-size: 1rem;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .photo-preview-container {
        display: flex;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .photo-preview {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-preview svg {
        width: 48px;
        height: 48px;
        color: #94a3b8;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-group input[type="file"] {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .help-text {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .modal-footer .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        border: none;
    }

    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            max-width: none;
        }

        .data-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

<script>
    function openPhotoModal(nis, nama, foto) {
        document.getElementById('modalNis').value = nis;
        document.getElementById('modalStudentName').textContent = 'Upload foto untuk: ' + nama;

        const preview = document.getElementById('photoPreview');
        if (foto) {
            preview.innerHTML = '<img src="/storage/' + foto + '" alt="Foto">';
        } else {
            preview.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>';
        }

        document.getElementById('photoModal').style.display = 'block';
    }

    function closePhotoModal() {
        document.getElementById('photoModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('photoModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection

