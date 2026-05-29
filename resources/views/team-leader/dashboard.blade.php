<x-agent-layout>
    <x-slot name="title">Team Leader Dashboard</x-slot>

    <x-slot name="headerContent">
        <div class="time-filter">
            <button onclick="window.location.href='{{ request()->fullUrlWithQuery(['time_filter' => 'today']) }}'"
                class="{{ $timeFilter === 'today' ? 'active' : '' }}">Today</button>
            <button onclick="window.location.href='{{ request()->fullUrlWithQuery(['time_filter' => 'week']) }}'"
                class="{{ $timeFilter === 'week' ? 'active' : '' }}">This Week</button>
            <button onclick="window.location.href='{{ request()->fullUrlWithQuery(['time_filter' => 'month']) }}'"
                class="{{ $timeFilter === 'month' ? 'active' : '' }}">This Month</button>
            <button onclick="window.location.href='{{ request()->fullUrlWithQuery(['time_filter' => 'quarter']) }}'"
                class="{{ $timeFilter === 'quarter' ? 'active' : '' }}">This Quarter</button>
        </div>
    </x-slot>

    <x-slot name="sidebar">
        <a class="sidebar-icon active" href="{{ route('team-leader.dashboard') }}" title="Dashboard">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('team-leader.tickets') }}" title="Tickets">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('team-leader.assign') }}" title="Assign Tickets">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
            </svg>
        </a>
        <a class="sidebar-icon" href="{{ route('team-leader.profile') }}" title="My Profile">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
        </a>
    </x-slot>

    <x-slot name="customStyles">
        .view-toggle {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        justify-content: flex-end;
        }

        .toggle-btn {
        padding: 8px 16px;
        border: 1px solid #E0E0E0;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        color: #666;
        transition: all 0.2s;
        }

        .toggle-btn:hover {
        background: #F5F5F5;
        }

        .toggle-btn.active {
        background: #1F4A5E;
        color: white;
        border-color: #1F4A5E;
        }

        .dashboard-view {
        display: none;
        animation: fadeIn 0.3s ease-in-out;
        }

        .dashboard-view.active {
        display: block;
        }

        @keyframes fadeIn {
        from {
        opacity: 0;
        transform: translateY(10px);
        }
        to {
        opacity: 1;
        transform: translateY(0);
        }
        }

        .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
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

        .today-stats-chart {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 10px auto 0;
        }

        .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
        }

        .card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .card-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 15px;
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

        .wo-available {
        display: flex;
        gap: 20px;
        align-items: center;
        }

        .wo-legend {
        flex: 1;
        }

        .legend-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 13px;
        }

        .legend-label {
        display: flex;
        align-items: center;
        gap: 6px;
        }

        .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        }

        .legend-value {
        font-weight: 600;
        color: #1a202c;
        }

        .chart-container {
        position: relative;
        width: 160px;
        height: 160px;
        flex-shrink: 0;
        }

        .achievement-bars {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        height: 180px;
        gap: 10px;
        margin-top: 10px;
        }

        .bar-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        }

        .bar {
        width: 100%;
        border-radius: 4px 4px 0 0;
        transition: all 0.3s;
        }

        .bar:hover {
        opacity: 0.8;
        }

        .bar-label {
        font-size: 10px;
        color: #666;
        font-weight: 500;
        text-align: center;
        }

        .grafik-chart {
        height: 240px;
        }

        .traffic-chart {
        height: 240px;
        }

        .search-section {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-top: 15px;
        }

        .search-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        }

        .search-input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #E0E0E0;
        border-radius: 6px;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        }

        .search-input:focus {
        outline: none;
        border-color: #1F4A5E;
        }

        .search-btn {
        padding: 10px 20px;
        background: #1F4A5E;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s;
        }

        .search-btn:hover {
        background: #2D6D8B;
        }

        .table-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #E0E0E0;
        }

        table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        }

        thead {
        background: #1F4A5E;
        color: white;
        }

        th {
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        }

        td {
        padding: 12px 15px;
        border-bottom: 1px solid #F0F0F0;
        }

        tbody tr:hover {
        background: #F9F9F9;
        }

        tbody tr:last-child td {
        border-bottom: none;
        }

        .inject-btn {
        padding: 6px 16px;
        background: #1F4A5E;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 11px;
        font-weight: 500;
        transition: all 0.2s;
        }

        .inject-btn:hover {
        background: #2D6D8B;
        }

        @media (max-width: 1024px) {
        .dashboard-grid {
        grid-template-columns: 1fr;
        }
        }

        @media (max-width: 768px) {
        .wo-available {
        flex-direction: column;
        }

        .chart-container {
        width: 140px;
        height: 140px;
        }
        }
    </x-slot>

    <!-- View Toggle -->
    <div class="view-toggle">
        <button class="toggle-btn active" onclick="switchView('overview')">Team Overview</button>
        <button class="toggle-btn" onclick="switchView('stats')">Team Stats</button>
    </div>

    <!-- Team Stats View (4 Cards) -->
    <div id="statsView" class="dashboard-view">
        <div class="stats-grid">
            <!-- Today Stats -->
            <div class="stat-card">
                <h3>Today Stats</h3>
                <div class="stat-item">
                    <span class="stat-label">WO Available</span>
                    <span class="stat-value">: {{ $stats['wo_available'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Consume</span>
                    <span class="stat-value blue">: {{ $stats['consume'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">ODS</span>
                    <span class="stat-value green">: {{ $stats['ods'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Closed</span>
                    <span class="stat-value red">: {{ $stats['closed'] }}</span>
                </div>
                <div class="today-stats-chart">
                    <canvas id="todayStatsChart"></canvas>
                </div>
            </div>

            <!-- Average Handling Time -->
            <div class="stat-card">
                <h3>Average Handling Time</h3>
                <div class="stat-number">12.5</div>
                <div style="margin-top: 20px;">
                    <div class="stat-item">
                        <span class="stat-label">All Consume</span>
                    </div>
                    <div class="stat-number">15.2</div>
                </div>
                <div style="margin-top: 20px;">
                    <div class="stat-item">
                        <span class="stat-label">AHT Last ticket</span>
                    </div>
                    <div class="stat-number">8.7</div>
                </div>
            </div>

            <!-- Total AUX/Online -->
            <div class="stat-card">
                <h3>Total AUX/Online</h3>
                <div class="stat-item">
                    <span class="stat-label">Online Time</span>
                </div>
                <div class="stat-number">7.5h</div>
                <div style="margin-top: 20px;">
                    <div class="stat-item">
                        <span class="stat-label">AUX Time</span>
                    </div>
                    <div class="stat-number">0.5h</div>
                </div>
            </div>

            <!-- Quality Operation Analytic -->
            <div class="stat-card">
                <h3>Quality Operation Analytic</h3>
                <div class="stat-item">
                    <span class="stat-label">Ticket Consume</span>
                </div>
                <div class="stat-number">{{ $stats['consume'] }}</div>
                <div style="margin-top: 20px;">
                    <div class="stat-item">
                        <span class="stat-label">Ticket Closed</span>
                    </div>
                    <div class="stat-number">{{ $stats['closed'] }}</div>
                </div>
            </div>
        </div>

        <!-- Charts in Stats View -->
        <div class="dashboard-grid">
            <!-- Grafik -->
            <div class="card">
                <h3 class="card-title">Grafik</h3>
                <div class="grafik-chart">
                    <canvas id="grafikChartStats"></canvas>
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
            <div class="card">
                <h3 class="card-title">Traffic Hourly</h3>
                <div class="traffic-chart">
                    <canvas id="trafficChartStats"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Overview View (Charts) -->
    <div id="overviewView" class="dashboard-view active">
        <div class="dashboard-grid">
            <!-- WO Available -->
            <div class="card">
                <h3 class="card-title">WO AVAILABLE</h3>
                <div class="wo-available">
                    <div class="wo-legend">
                        <div class="legend-item">
                            <div class="legend-label">
                                <div class="legend-color" style="background: #4CAF50;"></div>
                                <span>Helpdesk C4</span>
                            </div>
                            <span class="legend-value">: {{ $stats['wo_available'] }}</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-label">
                                <div class="legend-color" style="background: #F44336;"></div>
                                <span>Besfixed</span>
                            </div>
                            <span class="legend-value">: {{ $stats['consume'] }}</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-label">
                                <div class="legend-color" style="background: #FFEB3B;"></div>
                                <span>Salam Simpatik</span>
                            </div>
                            <span class="legend-value">: {{ $stats['closed'] }}</span>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="woChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik Achievement -->
            <div class="card">
                <h3 class="card-title">Grafik Achievement</h3>
                <div class="achievement-bars">
                    <div class="bar-wrapper">
                        <div class="bar" style="background: #4CAF50; height: 100%;"></div>
                        <span class="bar-label">C4 Area 1</span>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="background: #4CAF50; height: 100%;"></div>
                        <span class="bar-label">C4 Area 2</span>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="background: #4CAF50; height: 100%;"></div>
                        <span class="bar-label">C4 Area 3</span>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="background: #4CAF50; height: 100%;"></div>
                        <span class="bar-label">C4 Area 4</span>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="background: #F44336; height: 80%;"></div>
                        <span class="bar-label">BESFIXED</span>
                    </div>
                    <div class="bar-wrapper">
                        <div class="bar" style="background: #FFEB3B; height: 100%;"></div>
                        <span class="bar-label">SALTIK</span>
                    </div>
                </div>
            </div>

            <!-- Grafik -->
            <div class="card">
                <h3 class="card-title">Grafik</h3>
                <div class="grafik-chart">
                    <canvas id="grafikChart"></canvas>
                </div>
            </div>

            <!-- Traffic Hourly -->
            <div class="card">
                <h3 class="card-title">Traffic Hourly - WO & LAPUL GAUL</h3>
                <div class="traffic-chart">
                    <canvas id="trafficChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Table -->
    <div class="search-section">
        <form id="searchForm" class="search-bar">
            @if(request('time_filter'))
                <input type="hidden" name="time_filter" value="{{ request('time_filter') }}">
            @endif
            <input type="text" id="searchInput" name="search" class="search-input"
                placeholder="Search Ticket, Customer, Agent..." value="{{ request('search') }}">
            <button type="submit" class="search-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </button>
        </form>

        <div class="table-wrapper">
            <table id="ticketsTable">
                <thead>
                    <tr>
                        <th>Tiket</th>
                        <th>Campaign</th>
                        <th>Site</th>
                        <th>Agent</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets->take(10) as $ticket)
                        <tr>
                            <td>IN{{ str_pad($ticket->idTicket, 8, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $ticket->topic ?? 'Helpdesk C4' }}</td>
                            <td>{{ $ticket->regional ?? 'Jakarta' }}</td>
                            <td>{{ $ticket->assignby ?? 'Reca' }}</td>
                            <td><button class="inject-btn"
                                    onclick="window.location='{{ route('team-leader.ticket.detail', $ticket->idTicket) }}'">View
                                    Ticket</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #999;">Tidak ada data untuk
                                keyword tersebut</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-slot name="additionalScripts">
        // WO Available Pie Chart
        const woCtx = document.getElementById('woChart').getContext('2d');
        new Chart(woCtx, {
        type: 'pie',
        data: {
        labels: ['Helpdesk C4', 'Besfixed', 'Salam Simpatik'],
        datasets: [{
        data: [{{ $stats['wo_available'] }}, {{ $stats['consume'] }}, {{ $stats['closed'] }}],
        backgroundColor: ['#4CAF50', '#F44336', '#FFEB3B'],
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
        }
        }
        });

        const todayCtx = document.getElementById('todayStatsChart').getContext('2d');
        new Chart(todayCtx, {
        type: 'doughnut',
        data: {
        labels: ['WO Available', 'Consume', 'ODS', 'Closed'],
        datasets: [{
        data: [{{ $stats['wo_available'] }}, {{ $stats['consume'] }}, {{ $stats['ods'] }}, {{ $stats['closed'] }}],
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

        // Grafik Stacked Bar Chart
        const grafikCtx = document.getElementById('grafikChart').getContext('2d');
        new Chart(grafikCtx, {
        type: 'bar',
        data: {
        labels: {!! json_encode($chartData['barLabels']) !!},
        datasets: [
        {
        label: 'Consume',
        data: {!! json_encode($chartData['barConsume']) !!},
        backgroundColor: '#4A90E2',
        stack: 'Stack 0'
        },
        {
        label: 'ODS',
        data: {!! json_encode($chartData['barOds']) !!},
        backgroundColor: '#7ED321',
        stack: 'Stack 0'
        },
        {
        label: 'Closed',
        data: {!! json_encode($chartData['barClosed']) !!},
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
        display: true,
        position: 'bottom',
        labels: {
        boxWidth: 10,
        padding: 8,
        font: {
        size: 10
        }
        }
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
        max: 1000
        }
        }
        }
        });

        // Traffic Hourly Multi-line Chart
        const trafficCtx = document.getElementById('trafficChart').getContext('2d');
        new Chart(trafficCtx, {
        type: 'line',
        data: {
        labels: {!! json_encode($chartData['lineLabels']) !!},
        datasets: [
        {
        label: 'Traffic Hourly',
        data: {!! json_encode($chartData['lineData']) !!},
        borderColor: '#7ED321',
        backgroundColor: 'transparent',
        tension: 0.4,
        borderWidth: 2
        }
        ]
        },
        options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
        legend: {
        display: true,
        position: 'bottom',
        labels: {
        boxWidth: 10,
        padding: 8,
        font: {
        size: 10
        }
        }
        }
        },
        scales: {
        y: {
        beginAtZero: true,
        max: 100
        }
        }
        }
        });


        // Grafik Chart for Stats View (same as agent dashboard)
        const grafikStatsCtx = document.getElementById('grafikChartStats').getContext('2d');
        new Chart(grafikStatsCtx, {
        type: 'bar',
        data: {
        labels: {!! json_encode($chartData['barLabels']) !!},
        datasets: [
        {
        label: 'Consume',
        data: {!! json_encode($chartData['barConsume']) !!},
        backgroundColor: '#4A90E2',
        stack: 'Stack 0'
        },
        {
        label: 'ODS',
        data: {!! json_encode($chartData['barOds']) !!},
        backgroundColor: '#7ED321',
        stack: 'Stack 0'
        },
        {
        label: 'Closed',
        data: {!! json_encode($chartData['barClosed']) !!},
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

        // Traffic Chart for Stats View (same as agent dashboard)
        const trafficStatsCtx = document.getElementById('trafficChartStats').getContext('2d');
        new Chart(trafficStatsCtx, {
        type: 'line',
        data: {
        labels: {!! json_encode($chartData['lineLabels']) !!},
        datasets: [{
        data: {!! json_encode($chartData['lineData']) !!},
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

        // View Toggle Functionality
        function switchView(view) {
        const overviewView = document.getElementById('overviewView');
        const statsView = document.getElementById('statsView');
        const toggleButtons = document.querySelectorAll('.toggle-btn');

        // Save active state to browser memory
        localStorage.setItem('xena_tl_dashboard_view', view);

        if (view === 'overview') {
        overviewView.classList.add('active');
        statsView.classList.remove('active');
        toggleButtons[0].classList.add('active');
        toggleButtons[1].classList.remove('active');
        } else {
        overviewView.classList.remove('active');
        statsView.classList.add('active');
        toggleButtons[0].classList.remove('active');
        toggleButtons[1].classList.add('active');
        }
        }

        // Restore active view seamlessly after page reload
        document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('xena_tl_dashboard_view');
        if (savedView) {
        switchView(savedView);
        }
        });

        // Make switchView function global
        window.switchView = switchView;

        // AJAX Auto-Search Functionality
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');

        async function performSearch(e) {
        if(e) e.preventDefault();

        const keyword = searchInput.value;
        const timeFilter = document.querySelector('input[name="time_filter"]')?.value || 'today';

        const tbody = document.querySelector('#ticketsTable tbody');
        tbody.innerHTML = `<tr>
            <td colspan="5" style="text-align:center; padding: 20px; color: #666;">Sedang memfilter data...</td>
        </tr>`;

        try {
        // Background fetch
        const url =
        `{{ route('team-leader.dashboard') }}?time_filter=${timeFilter}&search=${encodeURIComponent(keyword)}`;
        const response = await fetch(url);
        const html = await response.text();

        // Parse HTML and replace table body
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTbody = doc.querySelector('#ticketsTable tbody');

        if (newTbody) {
        tbody.innerHTML = newTbody.innerHTML;
        }

        // Update URL without reloading (to preserve state if refreshed later)
        window.history.pushState({}, '', url);
        } catch (err) {
        tbody.innerHTML = `<tr>
            <td colspan="5" style="text-align:center; padding: 20px; color: red;">Gagal memuat data.</td>
        </tr>`;
        }
        }

        // Trigger search on typing with debounce (delay 500ms so it doesn't spam server)
        let debounceTimer;
        searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => performSearch(), 500);
        });

        searchForm.addEventListener('submit', performSearch);
    </x-slot>
</x-agent-layout>