<x-agent-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    
    <x-slot name="headerContent">
        <div class="time-filter">
            <button class="active">Today</button>
            <button>This Week</button>
            <button>This Month</button>
            <button>This Quarter</button>
        </div>
    </x-slot>
    
    <x-slot name="sidebar">
        <a class="sidebar-icon active" href="{{ route('admin.dashboard') }}" title="Dashboard">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('admin.users.index') }}" title="Users">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('admin.reports.index') }}" title="Reports">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('admin.settings.index') }}" title="Settings">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
            </svg>
        </a>
    </x-slot>
    
    <x-slot name="customStyles">
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 15px;
            flex-shrink: 0;
        }
        .stat-card {
            background: #FFFFFF;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .stat-card h3 {
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .stat-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .stat-label {
            color: #666;
        }
        .stat-value {
            font-weight: 600;
        }
        .stat-value.blue { color: #4A90E2; }
        .stat-value.green { color: #7ED321; }
        .stat-value.red { color: #D0021B; }
        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-top: 5px;
        }
        .chart-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
            flex-shrink: 0;
            min-height: 280px;
        }
        .chart-card {
            background: #FFFFFF;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .chart-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .chart-wrapper {
            flex: 1;
            position: relative;
            min-height: 0;
        }
        .chart-legend {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            font-size: 11px;
            flex-shrink: 0;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }
        .table-card {
            background: #FFFFFF;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            min-height: 300px;
            margin: 10px 0 30px 0;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            flex-shrink: 0;
        }
        .table-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        .table-wrapper {
            flex: 1;
            overflow: auto;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 800px;
        }
        thead {
            background: #1F4A5E;
            color: #FFFFFF;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        th {
            padding: 12px 15px;
            text-align: left;
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            z-index: 10;
            white-space: nowrap;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        tbody tr:hover {
            background: #F9F9F9;
        }
        .today-stats-chart {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }
    </x-slot>
    
    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Today Stats -->
        <div class="stat-card">
            <h3>Today Stats</h3>
            <div class="stat-item">
                <span class="stat-label">WO Available</span>
                <span class="stat-value">: 20</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Consume</span>
                <span class="stat-value blue">: 25</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">ODS</span>
                <span class="stat-value green">: 25</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Closed</span>
                <span class="stat-value red">: 10</span>
            </div>
            <div class="today-stats-chart">
                <canvas id="todayStatsChart"></canvas>
            </div>
        </div>

        <!-- Average Handling Time -->
        <div class="stat-card">
            <h3>Average Handling Time</h3>
            <div class="stat-number">0.00</div>
            <div style="margin-top: 20px;">
                <div class="stat-item">
                    <span class="stat-label">All Consume</span>
                </div>
                <div class="stat-number">0.00</div>
            </div>
            <div style="margin-top: 20px;">
                <div class="stat-item">
                    <span class="stat-label">AHT Last ticket</span>
                </div>
                <div class="stat-number">0.00</div>
            </div>
        </div>

        <!-- Total AUX/Online -->
        <div class="stat-card">
            <h3>Total AUX/Online</h3>
            <div class="stat-item">
                <span class="stat-label">Online Time</span>
            </div>
            <div class="stat-number">0.00</div>
            <div style="margin-top: 20px;">
                <div class="stat-item">
                    <span class="stat-label">AUX Time</span>
                </div>
                <div class="stat-number">0.00</div>
            </div>
        </div>

        <!-- Quality Operation Analytic -->
        <div class="stat-card">
            <h3>Quality Operation Analytic</h3>
            <div class="stat-item">
                <span class="stat-label">Ticket Consume</span>
            </div>
            <div class="stat-number">0.00</div>
            <div style="margin-top: 20px;">
                <div class="stat-item">
                    <span class="stat-label">Ticket Closed</span>
                </div>
                <div class="stat-number">0.00</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="chart-section">
        <!-- Grafik -->
        <div class="chart-card">
            <h3>Grafik</h3>
            <div class="chart-wrapper">
                <canvas id="grafikChart"></canvas>
            </div>
            <div class="chart-legend">
                <div class="legend-item">
                    <div class="legend-color" style="background: #4A90E2;"></div>
                    <span>Consume</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #7ED321;"></div>
                    <span>ODS</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #D0021B;"></div>
                    <span>Closed</span>
                </div>
            </div>
        </div>

        <!-- Traffic Hourly -->
        <div class="chart-card">
            <h3>Traffic Hourly</h3>
            <div class="chart-wrapper">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-header">
            <div class="table-title">Ticket Report</div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>KIP / SYMPTOMP</th>
                        <th>Quality / Agent Name</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>IN166714226</td>
                        <td>GAGAL REDEEM POINT</td>
                        <td>Reca</td>
                        <td>2025-11-01</td>
                        <td><span style="color: #7ED321; font-weight: 600;">Closed</span></td>
                    </tr>
                    <tr>
                        <td>IN166714227</td>
                        <td>TIDAK BISA LOGIN</td>
                        <td>Viona</td>
                        <td>2025-11-01</td>
                        <td><span style="color: #7ED321; font-weight: 600;">Closed</span></td>
                    </tr>
                    <tr>
                        <td>IN166714228</td>
                        <td>ERROR PEMBAYARAN</td>
                        <td>Angga</td>
                        <td>2025-11-02</td>
                        <td><span style="color: #7ED321; font-weight: 600;">Closed</span></td>
                    </tr>
                    <tr>
                        <td>IN166714229</td>
                        <td>LUPA PASSWORD</td>
                        <td>Yugo</td>
                        <td>2025-11-02</td>
                        <td><span style="color: #7ED321; font-weight: 600;">Closed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <x-slot name="additionalScripts">
        // Today Stats Pie Chart
        const todayCtx = document.getElementById('todayStatsChart').getContext('2d');
        new Chart(todayCtx, {
            type: 'doughnut',
            data: {
                labels: ['WO Available', 'Consume', 'ODS', 'Closed'],
                datasets: [{
                    data: [20, 25, 25, 10],
                    backgroundColor: ['#000000', '#4A90E2', '#7ED321', '#D0021B'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '50%'
            }
        });

        // Grafik Bar Chart
        const grafikCtx = document.getElementById('grafikChart').getContext('2d');
        new Chart(grafikCtx, {
            type: 'bar',
            data: {
                labels: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10'],
                datasets: [
                    {
                        label: 'Consume',
                        data: [40, 60, 40, 30, 20, 40, 45, 70, 75, 75],
                        backgroundColor: '#4A90E2',
                        stack: 'Stack 0'
                    },
                    {
                        label: 'ODS',
                        data: [20, 0, 40, 20, 30, 0, 20, 10, 10, 10],
                        backgroundColor: '#7ED321',
                        stack: 'Stack 0'
                    },
                    {
                        label: 'Closed',
                        data: [15, 25, 0, 10, 5, 25, 0, 10, 5, 5],
                        backgroundColor: '#D0021B',
                        stack: 'Stack 0'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        max: 110
                    }
                }
            }
        });

        // Traffic Hourly Line Chart
        const trafficCtx = document.getElementById('trafficChart').getContext('2d');
        new Chart(trafficCtx, {
            type: 'line',
            data: {
                labels: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09'],
                datasets: [{
                    data: [5, 3, 6, 4, 3, 8, 9, 7, 5, 6],
                    borderColor: '#7ED321',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 9,
                        ticks: {
                            stepSize: 2
                        }
                    }
                }
            }
        });
    </x-slot>
</x-agent-layout>
