<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu ID Siswa - {{ $siswa->nama }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* ID Card - CR80 Standard Size (85.6mm x 54mm) */
        .id-card {
            width: 324px;
            height: 204px;
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            box-shadow:
                0 10px 40px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            overflow: hidden;
            position: relative;
        }

        /* Header with gradient */
        .id-card-header {
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .id-card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        }

        .header-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .school-name {
            color: white;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .school-logo {
            width: 28px;
            height: 28px;
            background: rgba(255,255,255,0.2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 12px;
        }

        /* Card Body */
        .id-card-body {
            padding: 14px 16px;
            display: flex;
            gap: 14px;
            align-items: stretch;
        }

        /* Photo Area */
        .photo-section {
            width: 70px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .photo-placeholder {
            width: 58px;
            height: 70px;
            background: linear-gradient(145deg, #e8ecf1 0%, #d1d5db 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            overflow: hidden;
        }

        .photo-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder svg {
            width: 24px;
            height: 24px;
        }

        /* QR Code */
        .qr-section {
            width: 72px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr-code {
            width: 68px;
            height: 68px;
            background: white;
            border-radius: 4px;
            padding: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .qr-code img {
            width: 100%;
            height: 100%;
        }

        .qr-label {
            font-size: 7px;
            color: #6b7280;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Student Info */
        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 6px;
            min-width: 0;
        }

        .info-row {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .info-label {
            font-size: 8px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .info-value {
            font-size: 11px;
            font-weight: 600;
            color: #1f2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .info-value.nis {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #4f46e5;
            letter-spacing: 0.5px;
        }

        .info-value.nama {
            font-size: 13px;
            color: #111827;
        }

        .info-value.kelas {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            color: #4338ca;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            width: fit-content;
        }

        /* Card Footer */
        .id-card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .footer-text {
            position: absolute;
            bottom: 4px;
            left: 0;
            right: 0;
            text-align: center;
            color: rgba(255,255,255,0.8);
            font-size: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .btn-back {
            background: white;
            color: #374151;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .btn-back:hover {
            background: #f9fafb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .btn-print {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(102, 126, 234, 0.4);
        }

        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn svg {
            width: 18px;
            height: 18px;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .action-buttons {
                display: none;
            }

            .id-card {
                box-shadow: none;
                border: 1px solid #e5e7eb;
            }
        }

        /* Decorative corner elements */
        .id-card::before,
        .id-card::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            opacity: 0.1;
            pointer-events: none;
        }

        .id-card::before {
            top: -20px;
            right: -20px;
            background: #667eea;
        }

        .id-card::after {
            bottom: -30px;
            left: -30px;
            background: #764ba2;
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
    <!-- ID Card -->
    <div class="id-card">
        <div class="id-card-header">
            <div class="header-content">
                <span class="school-name">{{ $sekolah->nama ?? 'SMK Negeri' }}</span>
                <div class="school-logo">
                    @if($sekolah->logo_url)
                        <img src="{{ $sekolah->logo_url }}" alt="Logo" style="width: 20px; height: 20px; object-fit: contain;">
                    @else
                        {{ substr($sekolah->nama ?? 'SMK', 0, 2) }}
                    @endif
                </div>
            </div>
        </div>

        <div class="id-card-body">
            <div class="photo-section">
                <div class="photo-placeholder">
                    @if($siswa->foto)
                        <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto {{ $siswa->nama }}">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    @endif
                </div>
            </div>

            <div class="qr-section">
                <div class="qr-code">
                    {!! QrCode::size(60)->generate($qrData) !!}
                </div>
                <span class="qr-label">Scan QR</span>
            </div>

            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">NIS</span>
                    <span class="info-value nis">{{ $siswa->nis }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-value nama">{{ $siswa->nama }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Kelas</span>
                    <span class="info-value kelas">{{ $siswa->kelas }}</span>
                </div>
            </div>
        </div>

        <div class="id-card-footer">
            <span class="footer-text">Kartu Identitas Siswa • Presensi Mandiri</span>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('qr-code.index') }}" class="btn btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>

        <button onclick="window.print()" class="btn btn-print">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Kartu
        </button>
    </div>
</body>
</html>

