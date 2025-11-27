<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>XENA - {{ $title ?? 'Agent Dashboard' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
            max-width: 100%;
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
            padding: 15px 15px 20px 15px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            height: calc(100vh - 70px);
            margin-left: 20px;
        }
        {{ $customStyles ?? '' }}
    </style>
    
    {{ $additionalStyles ?? '' }}
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">XENA</div>
        <div class="header-right">
            {{ $headerContent ?? '' }}
            
            <!-- Notification Bell -->
            <x-notification-bell />
            
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
            {{ $sidebar }}
        </div>

        <!-- Main Content -->
        <div class="main-content">
            {{ $slot }}
        </div>
    </div>

    <script>
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
        
        {{ $additionalScripts ?? '' }}
    </script>
</body>
</html>
