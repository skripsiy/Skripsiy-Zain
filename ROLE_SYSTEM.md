# Sistem Role - XENA Ticket Distribution

## Role yang Tersedia

Sistem ini memiliki 3 role utama:

1. **Admin** - Memiliki akses penuh ke seluruh sistem
2. **Team Leader** - Dapat mengelola tim dan mendistribusikan tiket
3. **Agent** - Dapat menangani tiket yang didistribusikan

## User Default

Berikut adalah user default yang telah dibuat:

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

## Cara Login

1. Buka browser dan akses: http://127.0.0.1:8000/login
2. Masukkan email dan password sesuai dengan role yang ingin digunakan
3. Klik tombol "Login"

## Menggunakan Middleware Role

Untuk melindungi route berdasarkan role, gunakan middleware `role`:

```php
// Hanya admin yang bisa akses
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'role:admin']);

// Admin dan Team Leader yang bisa akses
Route::get('/tickets/manage', function () {
    return view('tickets.manage');
})->middleware(['auth', 'role:admin,team_leader']);

// Semua role yang bisa akses
Route::get('/tickets/view', function () {
    return view('tickets.view');
})->middleware(['auth', 'role:admin,team_leader,agent']);
```

## Helper Methods di User Model

Anda dapat menggunakan helper methods berikut untuk mengecek role user:

```php
// Cek apakah user adalah admin
if (auth()->user()->isAdmin()) {
    // Kode untuk admin
}

// Cek apakah user adalah team leader
if (auth()->user()->isTeamLeader()) {
    // Kode untuk team leader
}

// Cek apakah user adalah agent
if (auth()->user()->isAgent()) {
    // Kode untuk agent
}
```

## Menggunakan di Blade Template

```blade
@if(auth()->user()->isAdmin())
    <p>Konten khusus admin</p>
@endif

@if(auth()->user()->isTeamLeader())
    <p>Konten khusus team leader</p>
@endif

@if(auth()->user()->isAgent())
    <p>Konten khusus agent</p>
@endif
```

## Database Schema

Tabel `users` memiliki kolom tambahan:
- **role**: ENUM('admin', 'team_leader', 'agent') DEFAULT 'agent'

## Membuat User Baru

Untuk membuat user baru dengan role tertentu:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Nama User',
    'email' => 'email@example.com',
    'role' => 'agent', // atau 'admin', 'team_leader'
    'password' => Hash::make('password'),
]);
```

## Catatan Keamanan

⚠️ **PENTING**: Pastikan untuk mengganti password default setelah deployment ke production!
