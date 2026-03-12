@extends('layouts.dashboard')

@section('title', 'Pencatat Kehadiran Siswa')

@section('styles')
<style>
    /* Prevent horizontal scroll on mobile */
    * {
        box-sizing: border-box;
    }

    body, html {
        overflow-x: hidden;
    }

    .recorder-container {
        padding: 1rem;
        max-width: 100%;
        margin: 0 auto;
        overflow-x: hidden;
    }
.recorder-container {
        padding: 1rem;
        max-width: 100%;
        margin: 0 auto;
    }

@media (max-width: 480px) {
        .recorder-container {
            padding: 0.25rem;
        }

        .card {
            padding: 0.75rem;
        }

        .card-title {
            font-size: 1rem;
        }

        .page-title {
            font-size: 1.25rem;
        }

        .presensi-table {
            font-size: 0.75rem;
        }

        .presensi-table th, .presensi-table td {
            padding: 0.4rem 0.2rem;
        }

        .jam-cell {
            font-size: 0.8rem;
        }

        .qr-reader {
            max-width: 100% !important;
            width: 100% !important;
            height: 160px !important;
            border-radius: 6px;
        }

        .qr-scanner-section p {
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .scanner-controls {
            margin-top: 0.75rem;
        }

        .scanner-controls {
            flex-direction: column;
            gap: 0.5rem;
        }

        .scanner-controls .btn {
            padding: 0.6rem;
            font-size: 0.85rem;
        }

        #scanner-status {
            font-size: 0.85rem;
        }

        .btn {
            padding: 0.6rem;
            font-size: 0.85rem;
        }

        .tab-button {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }
    }

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

    /* Alert Messages */
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }

    .alert-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
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

    /* Main Grid */
    .recorder-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1024px) {
        .recorder-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Card Styles */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    /* QR Scanner */
    .qr-scanner-section {
        text-align: center;
    }

    /* Fix QR preview overlap */
    #qr-reader {
        position: relative !important;
        z-index: 1 !important;
    }

    .scanner-controls {
        position: relative;
        z-index: 10;
        margin-top: 1rem;
    }

    #qr-video {
        width: 100%;
        max-width: 300px;
        border-radius: 8px;
        margin: 1rem 0;
        border: 2px solid #e2e8f0;
        display: none;
    }

    .qr-scanner-result {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        display: none;
    }

    .qr-scanner-result.success {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
        display: block;
    }

    /* Search Container */
    .search-container {
        position: relative;
    }

    /* Search Form */
    .search-section {
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9375rem;
        color: #1e293b;
        background: white;
        transition: all 0.2s;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Mobile Search Optimizations */
    @media (max-width: 480px) {
        .form-label {
            font-size: 0.8rem;
        }

        .form-input {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        .search-dropdown {
            max-height: 250px;
            z-index: 1000;
        }

        .dropdown-item {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }

        .dropdown-item-name {
            font-size: 0.85rem;
            margin-bottom: 0.125rem;
        }

        .dropdown-item-nis {
            font-size: 0.75rem;
        }

        .tabs {
            gap: 0.25rem;
        }

        .tab-button {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
        width: 100%;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-success {
        background: linear-gradient(135deg, #86efac 0%, #4ade80 100%);
        color: #166534;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        width: auto;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(74, 222, 128, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #fca5a5 0%, #f87171 100%);
        color: #7f1d1d;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        width: auto;
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(248, 113, 113, 0.3);
    }

    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }

    /* Presensi Table */
    /* Presensi Table - Mobile Optimized */
    .presensi-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        table-layout: fixed;
        font-size: 0.85rem;
    }

    .presensi-table th,
    .presensi-table td {
        padding: 0.6rem 0.4rem;
        font-size: 0.82rem;
        border: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .presensi-table th {
        text-align: center;
        font-size: 0.78rem;
        font-weight: 600;
        background: #f8fafc;
    }

    .presensi-table th.nis-cell,
    .presensi-table td.nis-cell {
        max-width: 60px;
        font-size: 0.75rem;
    }

    .presensi-table th:nth-child(2),
    .presensi-table td:nth-child(2) {
        max-width: 80px;
    }

    .presensi-table .status-badge {
        padding: 0.2rem 0.4rem;
        font-size: 0.65rem;
        white-space: nowrap;
    }

    @media (max-width: 480px) {
        .presensi-table {
            font-size: 0.75rem;
        }

        .presensi-table th,
        .presensi-table td {
            padding: 0.4rem 0.2rem;
            font-size: 0.7rem;
        }

        .presensi-table .status-badge {
            padding: 0.15rem 0.3rem;
            font-size: 0.6rem;
        }
    }

    .presensi-table thead {
        background: #f8fafc;
    }

    .presensi-table th {
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e2e8f0;
    }

    .presensi-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .presensi-table tbody tr:hover {
        background: #f8fafc;
    }

    .nis-cell {
        font-weight: 600;
        color: #667eea;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-hadir {
        background: #dcfce7;
        color: #166534;
    }

    .status-belum {
        background: #fee2e2;
        color: #991b1b;
    }

.jam-cell {
        font-family: 'Courier New', monospace;
        font-size: 1rem;
        font-weight: 600;
        text-align: center;
    }

    .actions-cell {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    /* Search Dropdown */
    .search-dropdown {
        position: fixed;
        top: auto;
        bottom: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 0.5rem;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 100vw;
        max-width: none;
    }

    .search-dropdown.show {
        display: block;
    }

    .dropdown-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        /* stack name and info vertically so kelas is visible */
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background: #f8fafc;
        padding-left: 1.5rem;
    }

    .dropdown-item-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .dropdown-item-nis {
        font-size: 0.875rem;
        color: #64748b;
    }

    /* Loading Spinner */
    .spinner {
        border: 3px solid #f1f5f9;
        border-top: 3px solid #667eea;
        border-radius possiede 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #64748b;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    /* Scanner Controls */
    .scanner-controls {
        display: flex;
        gap: 0.75rem;
        margin: 1rem 0;
        flex-wrap: wrap;
    }

    .scanner-controls button {
        flex: 1;
        min-width: 120px;
    }

    @media (max-width: 480px) {
        .scanner-controls {
            gap: 0.5rem;
        }
    }

    /* Tabs for switching views */
    .tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 0.5rem;
        flex-wrap: wrap;
    }

    .tab-button {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-weight: 500;
        color: #64748b;
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tab-button.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .tab-button:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Presensi Info */
    .presensi-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .info-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .info-label {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
</style>

<!-- Fixed QR Library CDN - reliable jsDelivr -->
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
window.addEventListener('load', () => {
    if (typeof Html5Qrcode !== 'undefined') {
        console.log('✅ QR Scanner loaded successfully');
    } else {
        console.error('❌ QR library failed to load');
    }
});
</script>
@endsection

@section('content')
<div class="recorder-container">
    <div class="page-header">
        <h1 class="page-title">Pencatat Kehadiran Siswa</h1>
        <p class="page-subtitle">Catat kehadiran siswa dengan scan QR Code atau pencarian nama</p>
    </div>

    @if ($isLibur)
        <div class="alert alert-warning">
            <strong>⚠️ Informasi:</strong> Hari ini adalah hari libur ({{ $liburReason }}). Presensi tidak dapat dicatat.
        </div>
    @endif

    <div class="recorder-grid">
        <!-- Left Column: QR Scanner & Search -->
        <div>
            <div class="card">
                <h2 class="card-title">Pencarian Siswa</h2>

                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab-button active" onclick="switchInputMethod('search', event)">🔍 Pencarian</button>
                    <button class="tab-button" onclick="switchInputMethod('qr', event)">📱 QR Code</button>
                </div>

                <!-- Search Tab -->
                <div id="search-tab" class="tab-content active">
                    <div class="search-section">
                        <div class="form-group">
                            <label class="form-label">Cari Siswa (NIS atau Nama)</label>
                            <div class="search-container">
                                <input
                                    type="text"
                                    id="search-input"
                                    class="form-input"
                                    placeholder="Ketik NIS atau nama siswa..."
                                    autocomplete="off">
                                <div id="search-dropdown" class="search-dropdown">
                                    <!-- Results will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="search-result" style="display: none;">
                        <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;" id="result-name"></div>
                            <div style="font-size: 0.875rem; color: #64748b;">
                                NIS: <span id="result-nis"></span> | Kelas: <span id="result-kelas"></span>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <button class="btn btn-success" onclick="recordPresensi('datang')">
                                <strong>✓</strong> Datang
                            </button>
                            <button class="btn btn-danger" onclick="recordPresensi('pulang')">
                                <strong>✓</strong> Pulang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- QR Code Tab -->
                <div id="qr-tab" class="tab-content">
                    <div class="qr-scanner-section">
                        <p style="color: #64748b; margin-bottom: 1rem;">Arahkan QR Code ke kamera</p>
                        <div id="qr-reader" style="width: 100%; max-width: 320px; height: 240px; border-radius: 8px; border: 2px solid #e2e8f0; background: #f8fafc; margin: 0 auto;"></div>

                        <div class="scanner-controls">
                            <button class="btn btn-primary btn-small" onclick="startQRScanner()">
                                <span id="scanner-status">▶ Mulai Scan</span>
                            </button>
                            <button class="btn btn-secondary btn-small" onclick="stopQRScanner()" style="display: none;" id="stop-scanner-btn">
                                ⏹ Hentikan Scan
                            </button>
                        </div>

<div id="qr-scanner-result" class="qr-scanner-result">
                            <div id="qr-result-name"></div>
                            <div id="qr-result-nis" style="font-size: 0.875rem; color: #64748b; margin-top: 0.5rem;"></div>
                            <div id="qr-buttons" style="display: none; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                                <button class="btn btn-success" onclick="recordPresensi('datang')">
                                    <strong>✓</strong> Datang
                                </button>
                                <button class="btn btn-danger" onclick="recordPresensi('pulang')">
                                    <strong>✓</strong> Pulang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Today's Presensi -->
        <div>
            <div class="card">
                <h2 class="card-title">Presensi Hari Ini ({{ \Carbon\Carbon::parse($today)->format('d/m/Y') }})</h2>

                <div class="presensi-info">
                    <div class="info-card">
                        <div class="info-label">Total Siswa</div>
                        <div class="info-value" id="total-count">{{ $students->count() }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Sudah Hadir</div>
                        <div class="info-value" id="present-count" style="color: #16a34a;">{{ $presentCount }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Belum Hadir</div>
                        <div class="info-value" id="absent-count" style="color: #dc2626;">{{ $absentCount }}</div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="presensi-table">
                        <thead>
                            <tr>
                                <th style="width: 45%;">Siswa</th>
                                <th style="width: 18%;">Datang</th>
                                <th style="width: 18%;">Pulang</th>
                                <th style="width: 19%;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="presensi-tbody">
                            @forelse($students as $student)
                                @php
                                    $presensi = $presensis[$student->user_id] ?? null;
                                    $jamDatang = $presensi?->jam_datang ? \Carbon\Carbon::parse($presensi->jam_datang)->format('H:i') : '-';
                                    $jamPulang = $presensi?->jam_pulang ? \Carbon\Carbon::parse($presensi->jam_pulang)->format('H:i') : '-';
                                    if ($presensi && $presensi->jam_datang && $presensi->jam_pulang) {
                                        $statusClass = 'status-hadir';
                                        $statusText = 'Hadir';
                                    } elseif ($presensi && $presensi->jam_datang) {
                                        $statusClass = 'status-hadir';
                                        $statusText = 'Datang';
                                    } else {
                                        $statusClass = 'status-belum';
                                        $statusText = 'Belum';
                                    }
                                @endphp
                                <tr data-student-id="{{ $student->id }}" data-user-id="{{ $student->user_id }}">
                                    <td style="vertical-align: top; padding-left: 0.4rem;">
                                        <div style="font-weight: 600; font-size: 0.85rem;">{{ Str::limit($student->nama, 12) }}</div>
                                        <div style="font-size: 0.7rem; color: #6b7280; font-weight: normal; margin-top: 0.1rem;">
                                            {{ $student->nis }} • {{ $student->kelas ?? $student->user->kelas ?? '-' }}
                                        </div>
                                    </td>
                                    <td style="font-family: monospace; font-size: 0.9rem; font-weight: 600;">
                                        {{ $jamDatang }}
                                    </td>
                                    <td style="font-family: monospace; font-size: 0.9rem; font-weight: 600;">
                                        {{ $jamPulang }}
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $statusClass }}" id="status-{{ $student->user_id }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
<td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                        Tidak ada siswa
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedStudentId = null;
let selectedStudentUserId = null;
let qrScanner = null;
let isScanning = false;

const searchInput = document.getElementById('search-input');
const searchDropdown = document.getElementById('search-dropdown');
const searchResult = document.getElementById('search-result');

// Search functionality
searchInput.addEventListener('input', async function(e) {
    const query = e.target.value.trim();
    if (query.length < 1) {
        searchDropdown.classList.remove('show');
        return;
    }

    try {
        const response = await fetch(`{{ route('presensi-recorder.search') }}?q=${encodeURIComponent(query)}`);
        const data = await response.json();

        searchDropdown.innerHTML = '';
        if (data.length > 0) {
            data.forEach(student => {
                const item = document.createElement('div');
                item.className = 'dropdown-item';
                item.innerHTML = `
                    <div class="dropdown-item-name">${student.nama}</div>
                    <div class="dropdown-item-nis">NIS: ${student.nis} | Kelas: ${student.kelas || '-'}</div>
                `;
                item.onclick = () => selectStudent(student);
                searchDropdown.appendChild(item);
            });
            searchDropdown.classList.add('show');
        } else {
            searchDropdown.innerHTML = '<div class="dropdown-item" style="cursor: default; justify-content: center;">Tidak ada hasil</div>';
            searchDropdown.classList.add('show');
        }
    } catch (error) {
        console.error('Search error:', error);
    }
});

function selectStudent(student) {
    selectedStudentId = student.id;
    selectedStudentUserId = student.user_id;
    document.getElementById('result-name').textContent = student.nama;
    document.getElementById('result-nis').textContent = student.nis;
    document.getElementById('result-kelas').textContent = student.kelas || '-';
    searchInput.value = student.nama;
    searchDropdown.classList.remove('show');
    searchResult.style.display = 'block';
}

function switchInputMethod(method, evt) {
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    if (evt && evt.target) evt.target.classList.add('active');
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    if (method === 'search') {
        document.getElementById('search-tab').classList.add('active');
        if (isScanning) stopQRScanner();
    } else {
        document.getElementById('qr-tab').classList.add('active');
        startQRScanner();
    }
}

function startQRScanner() {
    if (isScanning || typeof Html5Qrcode === 'undefined') {
        if (typeof Html5Qrcode === 'undefined') showNotification('QR library not loaded. Refresh page.', 'error');
        return;
    }

    isScanning = true;
    document.getElementById('scanner-status').textContent = '🔄 Starting camera...';
    document.getElementById('stop-scanner-btn').style.display = 'block';

    qrScanner = new Html5Qrcode("qr-reader");

    const config = { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 };

    qrScanner.start(
        { facingMode: 'environment' },
        config,
        (decodedText) => {
            console.log('✅ QR scanned:', decodedText);
            handleQRCode(decodedText);
            stopQRScanner();
        },
        (error) => console.debug('Scan frame error:', error)
    ).then(() => {
        console.log('✅ Scanner started');
        document.getElementById('scanner-status').textContent = '✅ Scanning active';
        showNotification('Camera ready - point QR code', 'success');
    }).catch(err => {
        console.error('Camera start failed:', err);
        showNotification('Camera access denied/blocked. Use manual search.', 'error');
        isScanning = false;
        document.getElementById('stop-scanner-btn').style.display = 'none';
        document.getElementById('scanner-status').textContent = '▶ Mulai Scan';
    });
}

function stopQRScanner() {
    if (qrScanner) {
        qrScanner.stop().then(() => console.log('Scanner stopped')).catch(console.error);
        qrScanner = null;
    }
    isScanning = false;
    document.getElementById('scanner-status').textContent = '▶ Mulai Scan';
    document.getElementById('stop-scanner-btn').style.display = 'none';
    document.getElementById('qr-scanner-result').classList.remove('success');
}

function handleQRCode(decodedText) {
    console.log('QR data:', decodedText);
    let nis = decodedText.trim();
    try {
        const json = JSON.parse(decodedText);
        nis = json.nis || json.NIS || nis;
    } catch (e) {}

    fetch(`{{ route('presensi-recorder.search') }}?q=${encodeURIComponent(nis)}`)
        .then(r => r.json())
        .then(data => {
            if (data.length) {
                const student = data[0];
                selectedStudentId = student.id;
                document.getElementById('qr-result-name').innerHTML = `<strong>${student.nama}</strong>`;
                document.getElementById('qr-result-nis').innerHTML = `NIS: ${student.nis} | Kelas: ${student.kelas || '-'}`;
document.getElementById('qr-scanner-result').classList.add('success');
                document.getElementById('qr-buttons').style.display = 'grid';
                showNotification(`Found: ${student.nama}`, 'success');
            } else {
                showNotification(`Student NIS "${nis}" not found`, 'error');
            }
        }).catch(console.error);
}

function recordPresensi(type) {
    if (!selectedStudentId) return alert('Pilih siswa dulu');
    recordAttendance(selectedStudentId, type);
}

function recordFromTable(id, type) {
    recordAttendance(id, type);
}

async function recordAttendance(id, type) {
    const response = await fetch('{{ route("presensi-recorder.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ siswa_id: id, tipe: type })
    });
    const result = await response.json();
    showNotification(result.message || 'Error', result.success ? 'success' : 'error');
    if (result.success) {
        updatePresensiTable();
        if (selectedStudentId == id) {
            selectedStudentId = null;
            searchResult.style.display = 'none';
            searchInput.value = '';
        }
    }
}

async function updatePresensiTable() {
    const response = await fetch(`{{ route('presensi-recorder.get-presensi') }}?tanggal={{ $today }}`);
    const data = await response.json();
    if (data.success) {
        let count = 0;
        data.data.forEach(p => {
            const uid = p.user_id;
            const row = document.querySelector(`[data-user-id="${uid}"]`);
            if (row) {
                if (p.jam_datang) document.getElementById(`jam-datang-${uid}`).textContent = p.jam_datang;
                if (p.jam_pulang) document.getElementById(`jam-pulang-${uid}`).textContent = p.jam_pulang;
                if (p.jam_datang) {
                    document.getElementById(`status-${uid}`).textContent = p.jam_pulang ? 'Hadir' : 'Datang';
                    document.getElementById(`status-${uid}`).className = 'status-badge status-hadir';
                    count++;
                }
            }
        });
        document.getElementById('present-count').textContent = count;
        document.getElementById('absent-count').textContent = {{ $students->count() }} - count;
    }
}

function showNotification(msg, type) {
    const n = document.createElement('div');
    n.className = `alert alert-${type}`;
    n.style.cssText = 'position:fixed;top:2rem;right:2rem;z-index:10000;max-width:400px;padding:1rem;border-radius:8px;';
    n.textContent = msg;
    document.body.appendChild(n);
    setTimeout(() => n.remove(), 4000);
}

document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
        searchDropdown.classList.remove('show');
    }
});

// Load complete
console.log('Presensi recorder loaded');
updatePresensiTable();
</script>
@endsection
