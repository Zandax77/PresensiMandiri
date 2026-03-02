@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('styles')
<style>
    .dashboard-container {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .dashboard-header {
        margin-bottom: 2rem;
    }

    .dashboard-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .dashboard-subtitle {
        color: #64748b;
        font-size: 0.9375rem;
    }

    /* Date Filter */
    .date-filter {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .date-filter-form {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .date-filter label {
        font-weight: 500;
        color: #374151;
    }

    .date-input {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9375rem;
        color: #1e293b;
    }

    .date-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-filter {
        padding: 0.5rem 1.25rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
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
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
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

    .stat-card-change {
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    /* Chart Section */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .chart-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    /* Table Section */
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

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 500;
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

    .percentage-bar {
        width: 100%;
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }

    .percentage-fill {
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
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

    @media (max-width: 768px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard Presensi Harian</h1>
        <p class="dashboard-subtitle">Monitoring kehadiran siswa per kelas - {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
    </div>

    <!-- Date Filter -->
    <div class="date-filter">
        <form method="GET" action="{{ route('dashboard') }}" class="date-filter-form">
            <label for="tanggal">Pilih Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" class="date-input" value="{{ $tanggal }}">
            <button type="submit" class="btn-filter">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16" style="display: inline; margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                Filter
            </button>
            <a href="{{ route('dashboard') }}" class="btn-filter" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%);">
                Hari Ini
            </a>
        </form>

        <!-- Real-time Controls -->
        <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" id="autoRefresh" checked style="width: 16px; height: 16px; cursor: pointer;">
                <span style="font-weight: 500; color: #374151;">Auto-refresh (30 detik)</span>
            </label>
            <span id="lastUpdated" style="font-size: 0.8125rem; color: #64748b;">
                Terakhir diperbarui: {{ now()->format('H:i:s') }}
            </span>
            <button type="button" id="refreshBtn" class="btn-filter" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14" style="display: inline; margin-right: 4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Siswa</span>
                <div class="stat-card-icon" style="background: #dbeafe;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#1e40af" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $totalSemuaSiswa }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Hadir</span>
                <div class="stat-card-icon" style="background: #dcfce7;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#166534" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $totalHadir }}</div>
            <div class="stat-card-change" style="color: #166534;">{{ $persentaseHadirGlobal }}%</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Izin</span>
                <div class="stat-card-icon" style="background: #dbeafe;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#1e40af" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $totalIzin }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Sakit</span>
                <div class="stat-card-icon" style="background: #fee2e2;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#991b1b" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $totalSakit }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Alfa</span>
                <div class="stat-card-icon" style="background: #f1f5f9;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#475569" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $totalAlfa }}</div>
        </div>
    </div>

    @if(count($statsPerKelas) > 0)
    <!-- Charts -->
    <div class="charts-grid">
        <!-- Bar Chart: Total vs Hadir per Kelas -->
        <div class="chart-card">
            <h3 class="chart-card-title">Jumlah Siswa vs Hadir per Kelas</h3>
            <div class="chart-container">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart: Status Distribution -->
        <div class="chart-card">
            <h3 class="chart-card-title">Distribusi Status Kehadiran</h3>
            <div class="chart-container">
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-card">
        <h3 class="table-card-title">Detail Presensi per Kelas - {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Total Siswa</th>
                    <th>Hadir</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Alfa</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statsPerKelas as $stat)
                <tr>
                    <td><strong>{{ $stat['kelas'] }}</strong></td>
                    <td>{{ $stat['total_siswa'] }}</td>
                    <td>
                        <span class="status-badge status-hadir">{{ $stat['hadir'] }}</span>
                    </td>
                    <td>
                        <span class="status-badge status-izin">{{ $stat['izin'] }}</span>
                    </td>
                    <td>
                        <span class="status-badge status-sakit">{{ $stat['sakit'] }}</span>
                    </td>
                    <td>
                        <span class="status-badge status-alfa">{{ $stat['alfa'] }}</span>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="percentage-bar" style="width: 80px;">
                                <div class="percentage-fill" style="width: {{ $stat['persentase_hadir'] }}%;"></div>
                            </div>
                            <span style="font-size: 0.8125rem; color: #64748b;">{{ $stat['persentase_hadir'] }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p>Belum ada data siswa. Silakan tambah data siswa terlebih dahulu.</p>
    </div>
    @endif
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Get the current tanggal from the page
    let currentTanggal = '{{ $tanggal }}';
    let barChart = null;
    let doughnutChart = null;
    let autoRefreshInterval = null;

    // Initial data from server-side rendering
    const initialStatsPerKelas = @json($statsPerKelas);
    const labels = initialStatsPerKelas.map(stat => stat.kelas);
    const totalSiswa = initialStatsPerKelas.map(stat => stat.total_siswa);
    const hadir = initialStatsPerKelas.map(stat => stat.hadir);

    // Function to fetch data from API
    async function fetchDashboardData() {
        try {
            const response = await fetch(`{{ route('dashboard.api') }}?tanggal=${currentTanggal}`);
            if (!response.ok) throw new Error('Failed to fetch data');
            return await response.json();
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
            return null;
        }
    }

    // Function to update stats cards
    function updateStatsCards(data) {
        document.querySelector('.stat-card:nth-child(1) .stat-card-value').textContent = data.totalSemuaSiswa;
        document.querySelector('.stat-card:nth-child(2) .stat-card-value').textContent = data.totalHadir;
        document.querySelector('.stat-card:nth-child(2) .stat-card-change').textContent = data.persentaseHadirGlobal + '%';
        document.querySelector('.stat-card:nth-child(3) .stat-card-value').textContent = data.totalIzin;
        document.querySelector('.stat-card:nth-child(4) .stat-card-value').textContent = data.totalSakit;
        document.querySelector('.stat-card:nth-child(5) .stat-card-value').textContent = data.totalAlfa;
    }

    // Function to update charts
    function updateCharts(data) {
        const statsPerKelas = data.statsPerKelas;

        // Prepare data for charts
        const labels = statsPerKelas.map(stat => stat.kelas);
        const totalSiswa = statsPerKelas.map(stat => stat.total_siswa);
        const hadir = statsPerKelas.map(stat => stat.hadir);
        const izin = statsPerKelas.map(stat => stat.izin);
        const sakit = statsPerKelas.map(stat => stat.sakit);
        const alfa = statsPerKelas.map(stat => stat.alfa);

        // Update Bar Chart
        if (barChart) {
            barChart.data.labels = labels;
            barChart.data.datasets[0].data = totalSiswa;
            barChart.data.datasets[1].data = hadir;
            barChart.update('none');
        }

        // Update Doughnut Chart
        if (doughnutChart) {
            doughnutChart.data.datasets[0].data = [data.totalHadir, data.totalIzin, data.totalSakit, data.totalAlfa];
            doughnutChart.update('none');
        }
    }

    // Function to update table
    function updateTable(data) {
        const tbody = document.querySelector('.table-card tbody');
        if (!tbody) return;

        const statsPerKelas = data.statsPerKelas;

        if (statsPerKelas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="empty-state">Tidak ada data</td></tr>';
            return;
        }

        let html = '';
        statsPerKelas.forEach(stat => {
            html += `
                <tr>
                    <td><strong>${stat.kelas}</strong></td>
                    <td>${stat.total_siswa}</td>
                    <td><span class="status-badge status-hadir">${stat.hadir}</span></td>
                    <td><span class="status-badge status-izin">${stat.izin}</span></td>
                    <td><span class="status-badge status-sakit">${stat.sakit}</span></td>
                    <td><span class="status-badge status-alfa">${stat.alfa}</span></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="percentage-bar" style="width: 80px;">
                                <div class="percentage-fill" style="width: ${stat.persentase_hadir}%;"></div>
                            </div>
                            <span style="font-size: 0.8125rem; color: #64748b;">${stat.persentase_hadir}%</span>
                        </div>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    // Function to update last updated timestamp
    function updateLastUpdated(timestamp) {
        document.getElementById('lastUpdated').textContent = 'Terakhir diperbarui: ' + timestamp;
    }

    // Main function to update dashboard
    async function updateDashboard() {
        const data = await fetchDashboardData();
        if (!data) return;

        updateStatsCards(data);
        updateCharts(data);
        updateTable(data);
        updateLastUpdated(data.lastUpdated);
    }

    // Initialize charts
    function initCharts() {
        // Bar Chart: Total vs Hadir per Kelas
        const barCtx = document.getElementById('barChart').getContext('2d');
        barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Siswa',
                        data: totalSiswa,
                        backgroundColor: 'rgba(102, 126, 234, 0.8)',
                        borderColor: 'rgba(102, 126, 234, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                    {
                        label: 'Hadir',
                        data: hadir,
                        backgroundColor: 'rgba(22, 163, 74, 0.8)',
                        borderColor: 'rgba(22, 163, 74, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Doughnut Chart: Status Distribution
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        doughnutChart = new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alfa'],
                datasets: [{
                    data: [
                        {{ $totalHadir }},
                        {{ $totalIzin }},
                        {{ $totalSakit }},
                        {{ $totalAlfa }}
                    ],
                    backgroundColor: [
                        'rgba(22, 163, 74, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(220, 38, 38, 0.8)',
                        'rgba(100, 116, 139, 0.8)'
                    ],
                    borderColor: [
                        'rgba(22, 163, 74, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(220, 38, 38, 1)',
                        'rgba(100, 116, 139, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    // Function to start/stop auto-refresh
    function toggleAutoRefresh(enabled) {
        if (enabled) {
            if (autoRefreshInterval) clearInterval(autoRefreshInterval);
            autoRefreshInterval = setInterval(updateDashboard, 30000); // 30 seconds
        } else {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize charts
        initCharts();

        // Set up auto-refresh
        const autoRefreshCheckbox = document.getElementById('autoRefresh');
        toggleAutoRefresh(autoRefreshCheckbox.checked);

        // Listen for checkbox changes
        autoRefreshCheckbox.addEventListener('change', function() {
            toggleAutoRefresh(this.checked);
        });

        // Manual refresh button
        document.getElementById('refreshBtn').addEventListener('click', function() {
            updateDashboard();
        });

        // Listen for date filter changes
        const tanggalInput = document.getElementById('tanggal');
        if (tanggalInput) {
            tanggalInput.addEventListener('change', function() {
                currentTanggal = this.value;
                updateDashboard();
            });
        }
    });
</script>
@endsection

