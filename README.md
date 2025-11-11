# XENA - Ticket Distribution System

Sistem Distribusi Tiket Keluhan berbasis web menggunakan Laravel PHP untuk mengelola dan mendistribusikan tiket keluhan ke tim terkait.

## 🚀 Fitur

- ✅ Sistem Login dengan Role-Based Access Control
- ✅ 3 Role User: Admin, Team Leader, dan Agent
- ✅ Tampilan Login Modern dan Responsif
- ✅ Database Schema yang Terstruktur
- ✅ Middleware untuk Proteksi Route berdasarkan Role

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/SQLite Database

## 🔧 Instalasi

1. Clone repository ini
```bash
git clone <repository-url>
cd ticket-distribution-system
```

2. Install dependencies
```bash
composer install
npm install
```

3. Copy file environment
```bash
cp .env.example .env
```

4. Generate application key
```bash
php artisan key:generate
```

5. Jalankan migrasi database
```bash
php artisan migrate
```

6. Seed database dengan user default
```bash
php artisan db:seed --class=UserSeeder
```

7. Build assets
```bash
npm run build
```

8. Jalankan development server
```bash
php artisan serve
```

Aplikasi akan berjalan di: http://127.0.0.1:8000

## 👥 User Default

Sistem sudah dilengkapi dengan 3 user default:

### Admin
- **Email**: admin@xena.com
- **Password**: password123
- **Role**: admin

### Team Leader
- **Email**: teamleader@xena.com
- **Password**: password123
- **Role**: team_leader

### Agent
- **Email**: agent@xena.com
- **Password**: password123
- **Role**: agent

## 🔐 Sistem Role

### Admin
- Akses penuh ke seluruh sistem
- Dapat mengelola user, tim, dan tiket
- Dapat melihat semua laporan

### Team Leader
- Dapat mengelola tim
- Dapat mendistribusikan tiket ke agent
- Dapat melihat laporan tim

### Agent
- Dapat melihat tiket yang didistribusikan
- Dapat menangani dan menyelesaikan tiket
- Dapat memberikan update status tiket

## 📚 Dokumentasi

- [Sistem Role](ROLE_SYSTEM.md) - Dokumentasi lengkap tentang sistem role
- [Database Schema](database_schema.sql) - Schema database lengkap

## 🛡️ Keamanan

⚠️ **PENTING**: Pastikan untuk mengganti semua password default sebelum deployment ke production!

## 📝 Struktur Database

### Tabel Users
- `id` - Primary key
- `name` - Nama user
- `email` - Email user (unique)
- `role` - Role user (admin, team_leader, agent)
- `password` - Password terenkripsi
- `created_at` - Timestamp pembuatan
- `updated_at` - Timestamp update terakhir

## 🔨 Penggunaan Middleware

Untuk melindungi route berdasarkan role:

```php
// Hanya admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'role:admin']);

// Admin dan Team Leader
Route::get('/tickets/manage', [TicketController::class, 'manage'])
    ->middleware(['auth', 'role:admin,team_leader']);

// Semua role
Route::get('/tickets', [TicketController::class, 'index'])
    ->middleware(['auth', 'role:admin,team_leader,agent']);
```

## 🎨 Teknologi yang Digunakan

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates, Tailwind CSS
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Breeze

## 📞 Support

Untuk pertanyaan atau bantuan, silakan hubungi tim development.

## 📄 License

Project ini menggunakan [MIT license](https://opensource.org/licenses/MIT).
