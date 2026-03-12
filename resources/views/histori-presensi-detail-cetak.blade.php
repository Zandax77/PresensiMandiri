<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Presensi - {{ $siswaData->nama ?? 'Siswa' }}</title>
    <style>
        * {box-sizing: border-box; margin:0; padding:0;}
        body {font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:12px; line-height:1.4; color:#1e293b;}
        .print-container {max-width:100%; margin:0 auto; padding:20px;}
        .header {text-align:center; margin-bottom:20px; padding-bottom:15px; border-bottom:2px solid #1e293b;}
        .school-header {display:flex; align-items:center; justify-content:center; gap:15px; margin-bottom:10px;}
        .school-logo {width:60px; height:60px; object-fit:contain; border-radius:50%;}
        .school-logo-placeholder {width:60px; height:60px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; color:#94a3b8;}
        .school-info {text-align:left;}
        .school-name {font-size:18px; font-weight:700; color:#1e293b; margin:0;}
        .school-address {font-size:12px; color:#64748b; margin:2px 0 0 0;}
        .header-title {font-size:18px; font-weight:700; text-transform:uppercase; margin-bottom:5px;}
        .header-subtitle {font-size:14px; color:#64748b;}
        .header-info {margin-top:15px; font-size:12px;}
        .header-info-row {display:flex; justify-content:center; gap:20px; margin-top:5px;}
        .stats-summary {display:flex; justify-content:center; gap:15px; margin-bottom:20px; flex-wrap:wrap;}
        .stat-item {background:#f8fafc; padding:10px 15px; border-radius:6px; border:1px solid #e2e8f0; text-align:center; min-width:80px;}
        .stat-label {font-size:10px; color:#64748b; text-transform:uppercase; margin-bottom:3px;}
        .stat-value {font-size:16px; font-weight:700;}
        .stat-value.hadir {color:#166534;}
        .stat-value.izin {color:#1e40af;}
        .stat-value.sakit {color:#991b1b;}
        .stat-value.alfa {color:#475569;}
        .table-container {overflow-x:auto;}
        table {width:80%; margin:0 auto 20px auto; border-collapse:collapse;}
        th, td {padding:8px 10px; text-align:left; border:1px solid #e2e8f0;}
        th {background:#f8fafc; font-weight:600; font-size:10px; text-transform:uppercase; color:#64748b;}
        td {font-size:11px;}
        tr:nth-child(even){background:#f8fafc;}
        .status-badge {display:inline-block; padding:2px 8px; border-radius:4px; font-size:10px; font-weight:600;}
        .status-hadir {background:#dcfce7; color:#166534;}
        .status-izin {background:#dbeafe; color:#1e40af;}
        .status-sakit {background:#fee2e2; color:#991b1b;}
        .status-alfa {background:#f1f5f9; color:#475569;}
    </style>
</head>
<body>
    <div class="print-container">
        <div class="header">
            <div class="school-header">
                @if($sekolah->logo_url)
                    <img src="{{ $sekolah->logo_url }}" class="school-logo" alt="Logo Sekolah">
                @else
                    <div class="school-logo-placeholder">{{ strtoupper(substr($sekolah->nama,0,1)) }}</div>
                @endif
                <div class="school-info">
                    <div class="school-name">{{ $sekolah->nama }}</div>
                    <div class="school-address">{{ $sekolah->alamat }}</div>
                </div>
            </div>
            <div class="header-title">Histori Presensi</div>
            <div class="header-subtitle">{{ $siswaData->nama ?? 'Siswa' }}</div>
            <div class="header-info-row">
                <div>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</div>
                <div>Kelas: {{ $siswaData->kelas ?? '-' }}</div>
                <div>NIS: {{ $siswaData->nis ?? '-' }}</div>
            </div>
        </div>

        <div class="stats-summary">
            <div class="stat-item"><div class="stat-label">Hadir</div><div class="stat-value hadir">{{ $stats['hadir'] }}</div></div>
            <div class="stat-item"><div class="stat-label">Izin</div><div class="stat-value izin">{{ $stats['izin'] }}</div></div>
            <div class="stat-item"><div class="stat-label">Sakit</div><div class="stat-value sakit">{{ $stats['sakit'] }}</div></div>
            <div class="stat-item"><div class="stat-label">Alfa</div><div class="stat-value alfa">{{ $stats['alfa'] }}</div></div>
            <div class="stat-item"><div class="stat-label">Total Hari</div><div class="stat-value">{{ $stats['total_hari'] }}</div></div>
            <div class="stat-item"><div class="stat-label">% Hadir</div><div class="stat-value">{{ $stats['persentase_hadir'] }}%</div></div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Status</th>
                        <th>Jam Datang</th>
                        <th>Jam Pulang</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historiData as $row)
                        <tr>
                            <td>{{ $row['tanggal_formatted'] }}</td>
                            <td>{{ $row['hari'] }}</td>
                            <td><span class="status-badge status-{{ $row['status'] ?? 'alfa' }}">{{ ucfirst($row['status']) }}</span></td>
                            <td>{{ $row['jam_datang'] ?? '-' }}</td>
                            <td>{{ $row['jam_pulang'] ?? '-' }}</td>
                            <td>{{ $row['keterangan'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>