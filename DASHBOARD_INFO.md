# Dashboard Information - XENA Ticket Distribution System

## 🎯 Dashboard Overview

Sistem ini memiliki 3 jenis dashboard berdasarkan role user:

### 1. Agent Dashboard
**Route**: `/agent/dashboard`  
**Access**: Role `agent`

#### Fitur:
- ✅ **Today Stats** - Statistik harian dengan pie chart
  - WO Available: 20
  - Consume: 25 (biru)
  - ODS: 25 (hijau)
  - Closed: 10 (merah)

- ✅ **Average Handling Time** - Waktu penanganan rata-rata
  - AHT
  - All Consume
  - AHT Last ticket

- ✅ **Total AUX/Online** - Waktu online dan AUX
  - Online Time
  - AUX Time

- ✅ **Quality Operation Analytic** - Analitik operasional
  - Ticket Consume
  - Ticket Closed

- ✅ **Grafik** - Bar chart stacked untuk visualisasi data
  - Menampilkan data Consume, ODS, dan Closed per hari

- ✅ **Traffic Hourly** - Line chart traffic per jam
  - Menampilkan traffic dari jam 00:00 - 09:00

- ✅ **Tabel Tiket** - Daftar tiket dengan informasi:
  - Ticket Number
  - KIP/SYMTOMP
  - Quality
  - Tombol Download

#### Komponen UI:
- Header dengan logo XENA (gradient)
- Time filter: Today, This Week, This Month, This Quarter
- User avatar dengan dropdown menu (nama dan role)
- Sidebar dengan icon navigasi
- Logout functionality

### 2. Team Leader Dashboard
**Route**: `/team-leader/dashboard`  
**Access**: Role `team_leader`

Dashboard placeholder untuk Team Leader (akan dikembangkan lebih lanjut)

### 3. Admin Dashboard
**Route**: `/admin/dashboard`  
**Access**: Role `admin`

Dashboard placeholder untuk Admin (akan dikembangkan lebih lanjut)

## 🔐 Login Flow

1. User mengakses `/login`
2. Memasukkan email dan password
3. Sistem memvalidasi kredensial
4. Redirect otomatis berdasarkan role:
   - **Admin** → `/admin/dashboard`
   - **Team Leader** → `/team-leader/dashboard`
   - **Agent** → `/agent/dashboard`

## 👥 Test Accounts

### Agent
```
Email: agent@xena.com
Password: password123
Dashboard: http://127.0.0.1:8000/agent/dashboard
```

### Team Leader
```
Email: teamleader@xena.com
Password: password123
Dashboard: http://127.0.0.1:8000/team-leader/dashboard
```

### Admin
```
Email: admin@xena.com
Password: password123
Dashboard: http://127.0.0.1:8000/admin/dashboard
```

## 🎨 Design Features

### Color Scheme
- **Primary Gradient**: #0C1D25 → #1F4A5E → #2D6D8B
- **Background**: #F5F5F5 (light gray)
- **Cards**: #FFFFFF (white)
- **Text**: #333333 (dark gray)

### Typography
- **Font Family**: Poppins (Google Fonts)
- **Weights**: 400 (Regular), 500 (Medium), 600 (Semi-bold), 700 (Bold)

### Charts
Menggunakan **Chart.js** untuk visualisasi data:
- Pie/Doughnut Chart untuk Today Stats
- Stacked Bar Chart untuk Grafik
- Line Chart untuk Traffic Hourly

## 🔒 Security

### Middleware Protection
Semua dashboard dilindungi dengan:
1. **auth** middleware - Memastikan user sudah login
2. **role** middleware - Memastikan user memiliki role yang sesuai

### Role-Based Access Control (RBAC)
```php
// Contoh penggunaan di route
Route::get('/agent/dashboard', [AgentDashboardController::class, 'index'])
    ->middleware(['auth', 'role:agent'])
    ->name('agent.dashboard');
```

## 📱 Responsive Design

Dashboard dirancang responsive dan dapat diakses dari:
- Desktop (optimal)
- Tablet
- Mobile (dengan adjustment)

## 🚀 Next Steps

### Untuk Agent Dashboard:
- [ ] Integrasi dengan real data dari database
- [ ] Implementasi filter waktu (Today, This Week, dll)
- [ ] Export data ke Excel/PDF
- [ ] Real-time update menggunakan WebSocket
- [ ] Notifikasi untuk tiket baru

### Untuk Team Leader Dashboard:
- [ ] Manajemen tim
- [ ] Distribusi tiket ke agent
- [ ] Monitoring performa tim
- [ ] Laporan tim

### Untuk Admin Dashboard:
- [ ] Manajemen user
- [ ] Manajemen role dan permission
- [ ] System settings
- [ ] Analytics dan reporting
- [ ] Audit log

## 📝 Notes

- Dashboard Agent sudah fully functional dengan UI yang sesuai design
- Data saat ini masih static/dummy untuk demo
- Chart.js sudah terintegrasi dan berfungsi dengan baik
- Logout functionality sudah tersedia di semua dashboard
