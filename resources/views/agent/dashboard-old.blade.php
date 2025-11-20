<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XENA - Agent Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #F5F5F5;
            min-height: 100vh;
        }
        .header {
            background: #FFFFFF;
            padding: 20px 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(90deg, #0C1D25 0%, #1F4A5E 56%, #2D6D8B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.5px;
        }
        .header-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .time-filter {
            display: flex;
            gap: 15px;
        }
        .time-filter button {
            padding: 8px 16px;
            border: none;
            background: transparent;
            color: #666;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .time-filter button:hover,
        .time-filter button.active {
            color: #1F4A5E;
            font-weight: 600;
        }
        .user-menu {
            position: relative;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1F4A5E 0%, #2D6D8B 100%);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }
        .user-dropdown {
            position: absolute;
            top: 50px;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            min-width: 200px;
            display: none;
            z-index: 1000;
        }
        .user-dropdown.show {
            display: block;
        }
        .user-info {
            padding: 15px;
            border-bottom: 1px solid #E5E5E5;
        }
        .user-name {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .user-role {
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }
        .logout-btn {
            width: 100%;
            padding: 12px 15px;
            border: none;
            background: transparent;
            color: #D0021B;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s;
        }
        .logout-btn:hover {
            background: #FFF5F5;
        }
        .menu-btn {
            width: 100%;
            padding: 12px 15px;
            border: none;
            background: transparent;
            color: #333;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
        }
        .menu-btn:hover {
            background: #F5F5F5;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 50px;
            background: #FFFFFF;
            padding: 15px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px;
            box-shadow: 2px 0 4px rgba(0,0,0,0.05);
        }
        .sidebar-icon {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #666;
            transition: all 0.2s;
            text-decoration: none;
        }
        .sidebar-icon:hover,
        .sidebar-icon.active {
            color: #1F4A5E;
        }
        .main-content {
            flex: 1;
            padding: 15px 15px 20px 15px; /* More padding at bottom */
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            height: calc(100vh - 70px);
            margin-left: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px; /* Increased bottom margin */
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
            margin-bottom: 25px; /* Increased bottom margin */
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
            margin: 10px 0 30px 0; /* More bottom margin */
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            flex-shrink: 0;
            gap: 10px;
        }
        .table-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        .table-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        @media (max-width: 768px) {
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .table-controls {
                width: 100%;
                justify-content: space-between;
            }
            
            .date-filter {
                flex: 1;
            }
        }
        .date-filter {
            display: flex;
            gap: 8px;
            align-items: center;
            padding: 6px 12px;
            border: 1px solid #D0D0D0;
            border-radius: 5px;
            background: #FFFFFF;
            font-size: 12px;
            transition: all 0.2s ease;
            min-width: 260px;
        }
        
        .date-filter:focus-within {
            border-color: #4a90e2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }
        .date-filter input[type="date"] {
            border: none;
            outline: none;
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            color: #333;
            cursor: pointer;
        }
        .date-filter input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
        }
        .calendar-btn {
            padding: 6px 12px;
            border: 1px solid #D0D0D0;
            background: #FFFFFF;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .calendar-btn:hover {
            background: #F5F5F5;
        }
        .download-btn {
            background: #1F4A5E !important;
            color: #FFFFFF !important;
            border: none !important;
            padding: 8px 16px !important;
            border-radius: 5px !important;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .download-btn:hover {
            background: #2D6D8B !important;
        }
        .table-wrapper {
            width: 100%;
            height: 100%;
            overflow-y: auto;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            margin: 10px 0 20px 0;
            padding-bottom: 10px; /* Add padding at bottom */
        }
        
        .table-wrapper::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .table-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .table-wrapper::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 800px; /* Ensure table has minimum width */
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">XENA</div>
        <div class="header-right">
            <div class="time-filter">
                <button class="active">Today</button>
                <button>This Week</button>
                <button>This Month</button>
                <button>This Quarter</button>
            </div>
            <div class="user-menu">
                <div class="user-avatar" onclick="toggleDropdown()">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">🚪 Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <a class="sidebar-icon active" href="{{ route('agent.dashboard') }}" title="Dashboard">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
            </a>
            <a class="sidebar-icon" href="{{ route('agent.tickets') }}" title="Tickets">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
            </a>
            <a class="sidebar-icon" href="{{ route('agent.profile') }}" title="My Profile">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
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
                    <div class="table-controls">
                        <div class="date-filter">
                            <span>📅</span>
                            <input type="date" id="startDate" value="">
                            <span>-</span>
                            <input type="date" id="endDate" value="">
                        </div>
                        <button class="calendar-btn" onclick="filterByDate()">
                            <span>🔍</span>
                            <span>Filter</span>
                        </button>
                        <button class="download-btn" onclick="downloadReport()">
                            <span>💾</span>
                            <span>Download</span>
                        </button>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table id="ticketTable">
                        <thead>
                            <tr>
                                <th>Ticket Code</th>
                                <th>KIP / SYMPTOMP</th>
                                <th>Quality / Agent Name</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="ticketTableBody">
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
                            <tr>
                                <td>IN166714230</td>
                                <td>APLIKASI LEMOT</td>
                                <td>Budi</td>
                                <td>2025-11-03</td>
                                <td><span style="color: #7ED321; font-weight: 600;">Closed</span></td>
                            </tr>
                            <tr>
                                <td>IN166714231</td>
                                <td>GAGAL TRANSAKSI</td>
                                <td>Siti</td>
                                <td>2025-11-03</td>
                                <td><span style="color: #7ED321; font-weight: 600;">Closed</span></td>
                            </tr>
                            <tr>
                                <td>IN166714232</td>
                                <td>NOTIFIKASI TIDAK MASUK</td>
                                <td>Reca</td>
                                <td>2025-11-03</td>
                                <td><span style="color: #7ED321; font-weight: 600;">Closed</span></td>
                            </tr>
                            <tr>
                                <td>IN166714233</td>
                                <td>DATA TIDAK SINKRON</td>
                                <td>Viona</td>
                                <td>2025-11-03</td>
                                <td><span style="color: #7ED321; font-weight: 600;">Closed</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
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
                labels: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '...'],
                datasets: [
                    {
                        label: 'Consume',
                        data: [40, 60, 40, 30, 20, 40, 45, 70, 75, 75, 0],
                        backgroundColor: '#4A90E2',
                        stack: 'Stack 0'
                    },
                    {
                        label: 'ODS',
                        data: [20, 0, 40, 20, 30, 0, 20, 10, 10, 10, 0],
                        backgroundColor: '#7ED321',
                        stack: 'Stack 0'
                    },
                    {
                        label: 'Closed',
                        data: [15, 25, 0, 10, 5, 25, 0, 10, 5, 5, 0],
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

        // Toggle user dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.user-avatar')) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }

        // Set default dates (today)
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('startDate').value = today;
        document.getElementById('endDate').value = today;

        // All ticket data (in real app, this would come from backend)
        const allTickets = [
            { code: 'IN166714226', symptomp: 'GAGAL REDEEM POINT', agent: 'Reca', date: '2025-11-01', status: 'Closed' },
            { code: 'IN166714227', symptomp: 'TIDAK BISA LOGIN', agent: 'Viona', date: '2025-11-01', status: 'Closed' },
            { code: 'IN166714228', symptomp: 'ERROR PEMBAYARAN', agent: 'Angga', date: '2025-11-02', status: 'Closed' },
            { code: 'IN166714229', symptomp: 'LUPA PASSWORD', agent: 'Yugo', date: '2025-11-02', status: 'Closed' },
            { code: 'IN166714230', symptomp: 'APLIKASI LEMOT', agent: 'Budi', date: '2025-11-03', status: 'Closed' },
            { code: 'IN166714231', symptomp: 'GAGAL TRANSAKSI', agent: 'Siti', date: '2025-11-03', status: 'Closed' },
            { code: 'IN166714232', symptomp: 'NOTIFIKASI TIDAK MASUK', agent: 'Reca', date: '2025-11-03', status: 'Closed' },
            { code: 'IN166714233', symptomp: 'DATA TIDAK SINKRON', agent: 'Viona', date: '2025-11-03', status: 'Closed' }
        ];

        // Filter tickets by date range
        function filterByDate() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                alert('Silakan pilih tanggal mulai dan tanggal akhir');
                return;
            }

            if (startDate > endDate) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                return;
            }

            const filteredTickets = allTickets.filter(ticket => {
                return ticket.date >= startDate && ticket.date <= endDate;
            });

            updateTable(filteredTickets);
        }

        // Update table with filtered data
        function updateTable(tickets) {
            const tbody = document.getElementById('ticketTableBody');
            tbody.innerHTML = '';

            if (tickets.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px; color: #999;">Tidak ada data untuk periode yang dipilih</td></tr>';
                return;
            }

            tickets.forEach(ticket => {
                const row = `
                    <tr>
                        <td>${ticket.code}</td>
                        <td>${ticket.symptomp}</td>
                        <td>${ticket.agent}</td>
                        <td>${ticket.date}</td>
                        <td><span style="color: #7ED321; font-weight: 600;">${ticket.status}</span></td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Download report as CSV
        function downloadReport() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                alert('Silakan pilih tanggal mulai dan tanggal akhir terlebih dahulu');
                return;
            }

            // Filter tickets based on date range
            const filteredTickets = allTickets.filter(ticket => {
                return ticket.date >= startDate && ticket.date <= endDate;
            });

            if (filteredTickets.length === 0) {
                alert('Tidak ada data untuk di-download pada periode yang dipilih');
                return;
            }

            // Create CSV content
            let csvContent = "Ticket Code,KIP/SYMPTOMP,Quality/Agent Name,Date,Status\n";
            
            filteredTickets.forEach(ticket => {
                csvContent += `${ticket.code},${ticket.symptomp},${ticket.agent},${ticket.date},${ticket.status}\n`;
            });

            // Create blob and download
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            
            const filename = `Ticket_Report_${startDate}_to_${endDate}.csv`;
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Show success message
            alert(`Report berhasil di-download: ${filename}`);
        }
    </script>
</body>
</html>
