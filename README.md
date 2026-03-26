# 🎫 XENA - Ticket Distribution System

Sistem Distribusi Tiket Keluhan berbasis web menggunakan Laravel untuk mengelola, mendistribusikan, dan memantau penanganan tiket keluhan pelanggan dengan sistem role-based access control yang komprehensif.

## 📖 Daftar Isi

1. [Tentang Project](#-tentang-project)
2. [Fitur Utama](#-fitur-utama)
3. [Fitur per Role](#-fitur-per-role)
4. [Package Laravel yang Digunakan](#-package-laravel-yang-digunakan)
5. [Teknologi Stack](#-teknologi-stack)
6. [Persyaratan Sistem](#-persyaratan-sistem)
7. [Instalasi](#-instalasi)
8. [Konfigurasi](#-konfigurasi)
9. [User Default](#-user-default)
10. [Struktur Database](#-struktur-database)
11. [Dokumentasi Tambahan](#-dokumentasi-tambahan)

---

## 🎯 Tentang Project

**XENA (Xena Efficient Network Assistant)** adalah aplikasi web manajemen tiket customer service yang dirancang untuk meningkatkan efisiensi penanganan keluhan pelanggan. Sistem ini menggunakan arsitektur role-based access control dengan 3 role utama: **Admin**, **Team Leader**, dan **Agent**. 

Aplikasi ini dikembangkan menggunakan framework Laravel 12 dengan Tailwind CSS untuk tampilan yang modern dan responsif. Sistem dilengkapi dengan fitur real-time notifications, activity logging, work session tracking, dan reporting yang komprehensif.

---

## ✨ Fitur Utama

### 🔐 Authentication & Authorization
- **Login System** - Login menggunakan email/username dengan validasi
- **Role-Based Access Control** - 3 level akses (Admin, Team Leader, Agent)
- **Session Management** - Pengelolaan sesi pengguna yang aman
- **Password Reset** - Fitur reset password (via Laravel Breeze)

### 🎫 Ticket Management
- **Create & Assign Tickets** - Pembuatan dan penugasan tiket ke agent
- **Priority System** - 5 level prioritas: Super Emergency, Emergency, Urgent, Medium, Low
- **Status Tracking** - Monitoring status tiket dari Open hingga Closed
- **Ticket History** - Log aktivitas perubahan tiket menggunakan Activity Log
- **Detail Ticket View** - Informasi lengkap tiket dengan semua field

### 📊 Dashboard & Reporting
- **Role-based Dashboard** - Dashboard khusus untuk setiap role
- **Statistics & Charts** - Statistik tiket, performance metrics
- **Custom Reports** - Laporan berdasarkan user, periode, status
- **Export Reports** - Download laporan dalam format Excel

### 🔔 Notification System
- **Real-time Notifications** - Notifikasi real-time untuk assignment dan updates
- **Notification Bell** - Indikator notifikasi yang belum dibaca
- **Mark as Read** - Sistem untuk menandai notifikasi sebagai sudah dibaca

### ⏱️ Work Session Tracking (Agent)
- **Online/Offline Status** - Toggle status ketersediaan agent
- **Shift Management** - Pencatatan waktu mulai dan akhir shift
- **AUX Time Tracking** - Tracking waktu auxiliary (break, meeting, dll)
- **Work Time Reports** - Laporan jam kerja dan produktivitas

### 👤 Profile Management
- **Edit Profile** - Update informasi personal (nama, email, username, phone)
- **Change Password** - Ganti password dengan validasi
- **User Information** - Campaign, Area, Site assignment

---

## 🎭 Fitur per Role

### 👑 ADMIN

**Dashboard:**
- Overview statistik seluruh sistem
- Total users, tickets per status
- Grafik performa sistem
- Activity logs sistem

**User Management:**
- ✅ Lihat daftar semua user
- ✅ Tambah user baru dengan role tertentu
- ✅ Edit role user (upgrade/downgrade role)
- ✅ Update status user (active/inactive)
- ✅ Hapus user
- ✅ Filter dan search user

**Reports & Analytics:**
- ✅ Lihat laporan tiket seluruh sistem
- ✅ Filter laporan berdasarkan:
  - User tertentu
  - Rentang tanggal
  - Status tiket
  - Priority
  - Regional/Witel
- ✅ **Export laporan ke Excel** dengan fitur:
  - Download all user reports (overview performa semua user)
  - Download all tickets dengan filter
  - Download tiket per user (assigned/solved/inbox)
  - File Excel dengan styling professional
  - Format: `.xlsx` dengan auto-sized columns
- ✅ View detail tiket dari laporan
- ✅ User performance report

**Settings:**
- ✅ Konfigurasi sistem
- ✅ System information
- ✅ Toggle features (notifications, auto-assign, dll)

**Notifications:**
- Notifikasi user registration baru
- Notifikasi perubahan kritis pada sistem
- System alerts

---

### 🎯 TEAM LEADER

**Dashboard:**
- Overview tiket tim
- Statistik assign/unassign tickets
- Performance metrics agent di bawahnya
- Grafik trending

**Ticket Management:**
- ✅ Lihat semua tiket (assigned & unassigned)
- ✅ View detail tiket lengkap
- ✅ Update informasi tiket
- ✅ Update status tiket
- ✅ Filter dan search tiket

**Assign Tickets:**
- ✅ Lihat daftar tiket yang belum di-assign
- ✅ Assign tiket ke agent tertentu
- ✅ Lihat status ketersediaan agent (online/offline)
- ✅ Reassign tiket ke agent lain

**Team Monitoring:**
- Monitor aktivitas agent
- Lihat workload distribution
- View agent availability

**Profile:**
- ✅ Edit informasi profile pribadi
- ✅ Ganti password
- ✅ View campaign, area, site assignment

**Notifications:**
- Notifikasi tiket baru yang perlu di-assign
- Notifikasi update dari agent
- Notifikasi escalation

---

### 👨‍💼 AGENT

**Dashboard:**
- Overview tiket yang di-assign ke agent
- Quick stats (total, open, closed tickets)
- Recent tickets
- Work session status (online/offline/aux)
- Time tracking summary

**My Tickets:**
- ✅ Lihat semua tiket yang di-assign
- ✅ View detail tiket
- ✅ Update progress tiket
- ✅ Update status tiket (On Progress, Pending, Solved, Closed)
- ✅ Update semua field tiket
- ✅ Filter dan search tiket pribadi

**Work Session Management:**
- ✅ **Toggle Online/Offline** - Ubah status ketersediaan
- ✅ **Start Shift** - Mulai shift kerja (otomatis saat toggle online)
- ✅ **AUX Mode** - Masuk mode auxiliary dengan kategori:
  - Break
  - Meeting
  - Training
  - Other
- ✅ **End AUX** - Kembali ke mode available
- ✅ **End Shift** - Akhiri shift kerja
- ✅ **View Work Stats** - Lihat statistik jam kerja hari ini:
  - Total working hours
  - AUX time
  - Available time
  - Productive time

**Ticket Updates:**
- Update field-field tiket:
  - Resume/notes
  - Classification
  - Topic & Topic Detail
  - Service Center info
  - Escalation status
  - Description
  - Dan lainnya

**Profile:**
- ✅ Edit informasi profile pribadi
- ✅ Ganti password
- ✅ View campaign, area, site assignment

**Notifications:**
- Notifikasi tiket baru yang di-assign
- Notifikasi deadline approaching
- Notifikasi dari Team Leader

---

## 📦 Package Laravel yang Digunakan

### Core Packages

#### 1. **laravel/framework** (v12.0)
Framework utama Laravel dengan fitur:
- Eloquent ORM untuk database
- Blade templating engine
- Authentication & Authorization
- Routing system
- Validation
- Queue management

#### 2. **laravel/breeze** (v2.3) - Dev Only
Starter kit untuk authentication:
- Login & Registration
- Password reset
- Email verification
- Profile management
- Pre-built Blade templates dengan Tailwind CSS

### Additional Packages

#### 3. **spatie/laravel-activitylog** (v4.10)
Package untuk logging aktivitas:
- **Kegunaan**: Mencatat semua perubahan pada tiket
- **Fitur**:
  - Log semua perubahan field tiket
  - Track user yang melakukan perubahan
  - Timestamp setiap aktivitas
  - Query activity history
- **Implementasi**: Digunakan pada model `Ticket` untuk audit trail

#### 4. **laravel/reverb** (v1.0)
WebSocket server untuk real-time communication:
- **Kegunaan**: Real-time notifications
- **Fitur**:
  - Broadcasting events
  - WebSocket connections
  - Real-time updates
- **Implementasi**: Notifikasi tiket baru, status updates

#### 5. **pusher/pusher-php-server** (v7.2)
Integration dengan Pusher untuk broadcasting:
- **Kegunaan**: Backend untuk real-time notifications
- **Fitur**:
  - Trigger events ke client
  - Channel management
  - Presence channels
- **Implementasi**: Work dengan Laravel Echo untuk notifications

#### 6. **laravel/tinker** (v2.10.1)
REPL untuk Laravel:
- **Kegunaan**: Testing dan debugging via CLI
- **Fitur**:
  - Interactive shell
  - Database queries
  - Model testing
  - Quick data manipulation

#### 7. **maatwebsite/excel** (v3.1)
Package untuk export/import Excel:
- **Kegunaan**: Export dan import data dalam format Excel
- **Fitur**:
  - Export data ke Excel (.xlsx, .xls, .csv)
  - Import data dari Excel
  - Custom styling dan formatting
  - Memory efficient untuk large datasets
  - Support untuk multiple sheets
- **Implementasi**: 
  - Export user reports
  - Export all tickets dengan filters
  - Export user-specific tickets (assigned/solved/inbox)


### Development Packages

#### 8. **fakerphp/faker** (v1.23)
Generate data dummy:
- **Kegunaan**: Seeding database dengan data testing
- **Implementasi**: UserSeeder dan TicketSeeder

#### 9. **laravel/pail** (v1.2.2)
Log viewer untuk development:
- **Kegunaan**: View dan filter application logs secara real-time
- **Fitur**:
  - Real-time log streaming
  - Filter by level/type
  - Colored output

#### 10. **laravel/pint** (v1.24)
Code formatter untuk PHP:
- **Kegunaan**: Maintain code style consistency
- **Fitur**:
  - PSR-12 styling
  - Auto-fix code style issues

#### 11. **laravel/sail** (v1.41)
Docker environment untuk Laravel:
- **Kegunaan**: Development environment menggunakan Docker
- **Fitur**:
  - Pre-configured services
  - Database, Redis, Mailhog

#### 12. **phpunit/phpunit** (v11.5.3)
Testing framework:
- **Kegunaan**: Unit testing dan feature testing
- **Fitur**:
  - Assertions
  - Mocking
  - Test coverage

---

## 🛠️ Teknologi Stack

### Backend
- **Framework**: Laravel 12.x (PHP 8.2+)
- **Database**: SQLite (development) / MySQL (production)
- **Queue**: Database driver
- **Session**: Database driver
- **Cache**: Database driver

### Frontend
- **Template Engine**: Blade
- **CSS Framework**: Tailwind CSS v3.1
- **JavaScript**: Alpine.js v3.4
- **HTTP Client**: Axios v1.11
- **Real-time**: Laravel Echo v2.2 + Pusher JS v8.4
- **Build Tool**: Vite v7.0

### Development Tools
- **Package Manager**: Composer (PHP), NPM (JavaScript)
- **Code Quality**: Laravel Pint
- **Testing**: PHPUnit
- **Log Viewer**: Laravel Pail
- **Process Manager**: Concurrently

---

## 📋 Persyaratan Sistem

### Minimum Requirements
- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Node.js**: >= 18.x
- **NPM**: >= 9.x
- **Database**: SQLite / MySQL >= 8.0
- **Web Server**: Apache / Nginx (optional untuk production)

### PHP Extensions Required
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd ta-xena
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Setup Environment

```bash
# Copy file .env.example menjadi .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Setup Database

```bash
# Jalankan migrasi untuk membuat tabel
php artisan migrate

# Seed database dengan data default
php artisan db:seed
```

**Note**: Database seeder akan membuat:
- 3 user default (admin, team leader, agent)
- Sample tickets untuk testing

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Jalankan Aplikasi

```bash
# Jalankan development server
php artisan serve

# Optional: Jalankan queue worker (untuk notifications)
php artisan queue:work

# Optional: Jalankan semua services sekaligus
composer run dev
```

Aplikasi akan berjalan di: **http://localhost:8000**

---

## ⚙️ Konfigurasi

### Environment Variables

Edit file `.env` untuk konfigurasi:

```env
# Application
APP_NAME=XENA
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
# Atau untuk MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=xena
# DB_USERNAME=root
# DB_PASSWORD=

# Queue
QUEUE_CONNECTION=database

# Filesystem
FILESYSTEM_DISK=local

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Broadcasting (untuk real-time notifications)
BROADCAST_CONNECTION=log
# Ubah ke pusher untuk production
```

### File Storage

Jika ingin menyimpan file uploads ke public storage:

```bash
php artisan storage:link
```

### Queue Worker

Untuk menjalankan queue worker (required untuk notifications):

```bash
# Development - auto-reload on code changes
php artisan queue:listen

# Production - lebih stabil
php artisan queue:work --tries=3
```

---

## 👥 User Default

Setelah menjalankan `php artisan db:seed`, sistem akan memiliki 3 user default:

### 🔴 Admin
```
Email    : admin@xena.com
Password : password123
Role     : admin
```

**Akses**: Full system access

---

### 🟡 Team Leader
```
Email    : teamleader@xena.com
Password : password123
Role     : team_leader
```

**Akses**: Ticket management, assign tickets, team monitoring

---

### 🟢 Agent
```
Email    : agent@xena.com
Password : password123
Role     : agent
```

**Akses**: Assigned tickets, work session tracking

---

⚠️ **PENTING**: Ganti semua password default sebelum deploy ke production!

---

## 🗄️ Struktur Database

### Tabel: `users`
Menyimpan data pengguna sistem

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| name | varchar(255) | Nama lengkap user |
| email | varchar(255) | Email (unique) |
| username | varchar(255) | Username untuk login |
| password | varchar(255) | Password (hashed) |
| role | varchar(50) | Role: admin, team_leader, agent |
| status | varchar(20) | Status: active, inactive |
| campaign | varchar(100) | Campaign assignment |
| area | varchar(100) | Area assignment |
| site | varchar(100) | Site location |
| phone | varchar(20) | Nomor telepon |
| email_verified_at | timestamp | Email verification time |
| remember_token | varchar(100) | Session token |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu update terakhir |

---

### Tabel: `tickets`
Menyimpan data tiket keluhan

| Field | Type | Description |
|-------|------|-------------|
| idTicket | bigint | Primary key |
| datereport | date | Tanggal laporan |
| jenisTicket | varchar | Jenis tiket |
| notelpCust | varchar | No telepon customer |
| namacust | varchar | Nama customer |
| idlaporan | varchar | ID laporan |
| detailticket | text | Detail keluhan |
| reportedpriority | varchar | Priority: super_emergency, emergency, urgent, medium, low |
| status | varchar | Status: open, assigned, on_progress, pending, solved, closed |
| regional | varchar | Regional area |
| witel | varchar | Witel area |
| gamas | varchar | GAMAS info |
| lapul | varchar | LAPUL info |
| gaul | varchar | GAUL info |
| resume | text | Resume penanganan |
| klasifikasi | varchar | Klasifikasi tiket |
| topic | varchar | Topik masalah |
| topicDetail | varchar | Detail topik |
| noSC | varchar | Nomor Service Center |
| statusSC | varchar | Status Service Center |
| validateClose | varchar | Validasi close |
| reasonnoODS | text | Reason no ODS |
| eksalasiTicket | varchar | Eskalasi tiket |
| eksalasiVia | varchar | Via eskalasi |
| PIC | varchar | Person in charge |
| contact | varchar | Contact info |
| responBE | text | Respon backend |
| description | text | Deskripsi lengkap |
| datesolved | date | Tanggal selesai |
| THT | datetime | Target handling time |
| condition | varchar | Kondisi |
| assignby | bigint | User ID yang assign (FK users) |
| solvedby | bigint | User ID yang solve (FK users) |
| escalationStatus | varchar | Status eskalasi |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu update terakhir |

---

### Tabel: `agent_work_sessions`
Menyimpan data sesi kerja agent

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| agent_id | bigint | User ID agent (FK users) |
| is_online | boolean | Status online/offline |
| shift_start | datetime | Waktu mulai shift |
| shift_end | datetime | Waktu akhir shift |
| aux_start | datetime | Waktu mulai AUX |
| aux_end | datetime | Waktu akhir AUX |
| aux_category | varchar | Kategori AUX (break, meeting, training, other) |
| total_working_hours | decimal | Total jam kerja |
| total_aux_hours | decimal | Total jam AUX |
| total_available_hours | decimal | Total jam available |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu update terakhir |

---

### Tabel: `activity_log`
Menyimpan log aktivitas (Spatie Activity Log)

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| log_name | varchar | Nama log |
| description | varchar | Deskripsi aktivitas |
| subject_type | varchar | Model type (polymorphic) |
| subject_id | bigint | Model ID (polymorphic) |
| causer_type | varchar | User type (polymorphic) |
| causer_id | bigint | User ID yang melakukan action |
| properties | json | Detail perubahan (old/new values) |
| event | varchar | Event type (created, updated, deleted) |
| batch_uuid | uuid | Batch identifier |
| created_at | timestamp | Waktu aktivitas |

---

### Tabel Lainnya
- `cache` - Cache storage
- `cache_locks` - Cache locks
- `sessions` - Session storage
- `jobs` - Queue jobs
- `job_batches` - Batch jobs
- `failed_jobs` - Failed queue jobs
- `password_reset_tokens` - Reset password tokens

---

## 📚 Dokumentasi Tambahan

Project ini dilengkapi dengan dokumentasi tambahan:

- **[ROLE_SYSTEM.md](ROLE_SYSTEM.md)** - Penjelasan detail sistem role dan permissions
- **[DASHBOARD_INFO.md](DASHBOARD_INFO.md)** - Informasi lengkap tentang dashboard setiap role
- **[PROFILE_SYSTEM.md](PROFILE_SYSTEM.md)** - Dokumentasi sistem profil pengguna
- **[TICKET_REPORT_FEATURE.md](TICKET_REPORT_FEATURE.md)** - Dokumentasi fitur laporan tiket
- **[database_schema.sql](database_schema.sql)** - Schema database lengkap

---

## 🔧 Troubleshooting

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: Database migration failed
```bash
# Drop semua tabel dan migrate ulang
php artisan migrate:fresh --seed
```

### Error: npm packages not found
```bash
# Clear cache dan reinstall
rm -rf node_modules package-lock.json
npm install
```

### Assets tidak ter-compile
```bash
# Development
npm run dev

# Production
npm run build
```

### Notifications tidak bekerja
Pastikan queue worker berjalan:
```bash
php artisan queue:work
```

---

## 🔐 Keamanan

### Production Checklist
- [ ] Set `APP_ENV=production` di `.env`
- [ ] Set `APP_DEBUG=false` di `.env`
- [ ] Ganti semua password default
- [ ] Generate fresh `APP_KEY`
- [ ] Gunakan HTTPS
- [ ] Set proper permission untuk storage folder
- [ ] Enable rate limiting
- [ ] Setup proper backup strategy
- [ ] Configure CORS properly
- [ ] Use strong database password

---

## 🚀 Deployment

### Production Setup

1. **Server Requirements**
   - PHP 8.2+
   - MySQL 8.0+
   - Nginx / Apache dengan SSL
   - Supervisor untuk queue worker

2. **Deploy Steps**
```bash
# Clone dan install
git clone <repo>
cd ta-xena
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Environment
cp .env.example .env
# Edit .env dengan konfigurasi production
php artisan key:generate

# Database
php artisan migrate --force
php artisan db:seed --force

# Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Setup Queue Worker dengan Supervisor**
```ini
[program:xena-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/ta-xena/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/ta-xena/storage/logs/worker.log
```

---

## 📞 Support & Kontribusi

Untuk pertanyaan, bug report, atau feature request, silakan hubungi tim development.

---

## 📄 License

Project ini menggunakan [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Credits

Dikembangkan dengan ❤️ menggunakan Laravel Framework

**Version**: 1.0.0
**Last Updated**: December 2025
