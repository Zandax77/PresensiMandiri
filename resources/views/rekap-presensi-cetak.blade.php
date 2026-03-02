<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Presensi - {{ $kelasDipilih ?? 'Semua Kelas' }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #1e293b;
        }

        .print-container {
            max-width: 100%;
            margin-left: 2cm;
            margin-right: 2cm;
            padding: 20px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #1e293b;
        }

        .school-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .school-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 50%;
        }

        .school-logo-placeholder {
            width: 60px;
            height: 60px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #94a3b8;
        }

        .school-info {
            text-align: left;
        }

        .school-name {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .school-address {
            font-size: 12px;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        .header-title {
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .header-subtitle {
            font-size: 14px;
            color: #64748b;
        }

        .header-info {
            margin-top: 15px;
            font-size: 12px;
        }

        .header-info-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 5px;
        }

        /* Stats Summary */
        .stats-summary {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .stat-item {
            background: #f8fafc;
            padding: 10px 15px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            text-align: center;
            min-width: 80px;
        }

        .stat-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .stat-value {
            font-size: 16px;
            font-weight: 700;
        }

        .stat-value.hadir { color: #166534; }
        .stat-value.izin { color: #1e40af; }
        .stat-value.sakit { color: #991b1b; }
        .stat-value.alfa { color: #475569; }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 80%;
            margin: 0 auto 20px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
        }

        td {
            font-size: 11px;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        /* Status badges for print */
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
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

        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-label {
            font-size: 11px;
            margin-bottom: 40px;
        }

        .signature-line {
            border-bottom: 1px solid #1e293b;
            margin-bottom: 5px;
            height: 30px;
        }

        .signature-name {
            font-size: 11px;
            font-weight: 600;
        }

        /* Print Styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-container {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }

        /* Screen styles */
        .no-screen {
            display: none !important;
        }

        @media screen {
            .btn-print {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 24px;
                background: #667eea;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
                z-index: 1000;
            }

            .btn-print:hover {
                background: #5568d3;
            }

            .btn-back {
                position: fixed;
                top: 20px;
                left: 20px;
                padding: 12px 24px;
                background: #64748b;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                text-decoration: none;
                z-index: 1000;
            }

            .btn-back:hover {
                background: #475569;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button (Screen Only) -->
    <a href="{{ route('rekap-presensi.index') }}" class="btn-back no-screen no-print" style="display: inline-block;">← Kembali</a>
    <button onclick="window.print()" class="btn-print no-screen no-print" style="display: inline-block;">🖨️ Cetak</button>

    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <!-- School Info -->
            <div class="school-header">
                @if($sekolah && $sekolah->logo_url)
                    <img src="{{ $sekolah->logo_url }}" alt="Logo" class="school-logo">
                @else
                    <div class="school-logo-placeholder">🏫</div>
                @endif
                <div class="school-info">
                    <h1 class="school-name">{{ $sekolah->nama ?? 'Nama Sekolah' }}</h1>
                    <p class="school-address">{{ $sekolah->alamat ?? 'Alamat Sekolah' }}</p>
                </div>
            </div>

            <div class="header-title">Rekap Presensi Siswa</div>
            <div class="header-subtitle">
                @if($kelasDipilih)
                    Kelas {{ $kelasDipilih }}
                @else
                    Semua Kelas
                @endif
            </div>
            <div class="header-info">
                <div class="header-info-row">
                    <span>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}</span>
                    <span>Total Hari: {{ count($dateRange) }} hari</span>
                    <span>Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="stats-summary">
            <div class="stat-item">
                <div class="stat-label">Izin</div>
                <div class="stat-value izin">{{ $overallStats['total_izin'] }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Sakit</div>
                <div class="stat-value sakit">{{ $overallStats['total_sakit'] }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Alfa</div>
                <div class="stat-value alfa">{{ $overallStats['total_alfa'] }}</div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Siswa</th>
                        <th style="width: 80px;">NIS</th>
                        <th style="width: 50px;">Izin</th>
                        <th style="width: 50px;">Sakit</th>
                        <th style="width: 50px;">Alfa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data['nama'] }}</td>
                            <td>{{ $data['nis'] }}</td>
                            <td><span class="status-badge status-izin">{{ $data['izin'] }}</span></td>
                            <td><span class="status-badge status-sakit">{{ $data['sakit'] }}</span></td>
                            <td><span class="status-badge status-alfa">{{ $data['alfa'] }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">Tidak ada data presensi dalam periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signature Section -->
        <div class="signature-section" style="justify-content: flex-end;">
            <div class="signature-box">
                <div class="signature-label">Wali Kelas</div>
                <div class="signature-line"></div>
                <div class="signature-name">
                    @if(auth()->user()->isWaliKelas())
                        {{ auth()->user()->name }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show buttons on screen only
        if (!window.matchMedia('print').matches) {
            document.querySelectorAll('.no-screen').forEach(el => {
                el.style.display = 'inline-block';
            });
        }
    </script>
</body>
</html>

