# 📊 Excel Export Feature Documentation

## Overview

XENA Ticket Distribution System dilengkapi dengan fitur **Excel Export** yang powerful menggunakan package **Laravel Excel (maatwebsite/excel)**. Fitur ini memungkinkan admin untuk mengekspor data reports dalam format Excel (.xlsx) dengan styling professional dan data yang komprehensif.

---

## 🎯 Fitur Export yang Tersedia

### 1. **Export User Reports** 
Export overview performa semua user dalam satu file Excel.

**Route:** `/admin/reports/export/users`

**Cara Akses:**
- Navigasi ke halaman **Admin → Reports**
- Klik tombol **"Export Excel"** di header halaman

**Data yang di-export:**
- User ID
- Name
- Email
- Username
- Role
- Status
- Campaign
- Area
- Site
- Phone
- Total Assigned Tickets
- Total Solved Tickets
- Inbox Tickets
- Account Created Date

**File Name Format:** `user_reports_YYYY-MM-DD_HHmmss.xlsx`

---

### 2. **Export All Tickets**
Export semua tiket dengan opsi filtering.

**Route:** `/admin/reports/export/tickets`

**Filter Options:**
- User ID (filter by specific user)
- Status (open, assigned, on_progress, pending, solved, closed)
- Priority (super_emergency, emergency, urgent, medium, low)
- Regional
- Witel
- Date From
- Date To

**Data yang di-export:**
- Ticket ID
- Date Report
- Jenis Ticket
- Customer Phone & Name
- ID Laporan
- Detail Ticket
- Priority & Status
- Regional & Witel
- GAMAS, LAPUL, GAUL
- Resume & Klasifikasi
- Topic & Topic Detail
- Service Center Info
- Escalation Details
- PIC & Contact
- Response & Description
- Date Solved & THT
- Assigned By & Solved By
- Timestamps

**File Name Format:** `all_tickets_YYYY-MM-DD_HHmmss.xlsx`

**Contoh Filter URL:**
```
/admin/reports/export/tickets?status=open&priority=urgent&date_from=2026-01-01
```

---

### 3. **Export User-Specific Tickets**
Export tiket untuk user tertentu dengan kategori (assigned/solved/inbox).

**Route:** `/admin/reports/export/user-tickets/{userId}`

**Cara Akses:**
- Di halaman **Admin → Reports**
- Klik salah satu user card untuk melihat detail
- Di modal detail user, klik tombol **"Export"**
- Pilih tipe export:
  - **Export Assigned Tickets** - Semua tiket yang di-assign ke user
  - **Export Solved Tickets** - Semua tiket yang di-solve oleh user
  - **Export Inbox Tickets** - Tiket dalam inbox user (status QUEUED)

**Parameter:**
- `type` - `assigned` | `solved` | `inbox`

**Data yang di-export:**
- Ticket ID
- Date Report
- Customer Name & Phone
- Jenis Ticket
- Detail
- Priority & Status
- Regional & Witel
- Resume
- Klasifikasi
- Topic
- Date Solved
- Created At

**File Name Format:** `{UserName}_{type}_tickets_YYYY-MM-DD_HHmmss.xlsx`

Contoh: `John_Doe_assigned_tickets_2026-01-05_135027.xlsx`

---

## 🎨 Styling & Formatting

Semua file Excel yang di-export memiliki professional formatting:

### Header Row Styling
- **Background Color:** Dark Blue (#2C5F7C)
- **Font Color:** White
- **Font Weight:** Bold
- **Font Size:** 12pt
- **Alignment:** Center, Middle
- **Border:** Thin black borders
- **Text Wrap:** Enabled

### Column Widths
- Auto-sized berdasarkan content
- Custom width untuk kolom tertentu (optimal readability)

### Data Formatting
- Tanggal: `Y-m-d` format (e.g., 2026-01-05)
- DateTime: `Y-m-d H:i:s` format (e.g., 2026-01-05 13:50:27)
- Text: Properly escaped dan formatted

---

## 🛠️ Technical Implementation

### Export Classes Location
```
app/Exports/
├── TicketsExport.php          # Export all tickets dengan filters
├── UserReportsExport.php      # Export user performance reports
└── UserTicketsExport.php      # Export user-specific tickets
```

### Routes
```php
// web.php
Route::get('/reports/export/users', [ReportController::class, 'exportUserReports'])
    ->name('reports.export.users');
    
Route::get('/reports/export/tickets', [ReportController::class, 'exportAllTickets'])
    ->name('reports.export.tickets');
    
Route::get('/reports/export/user-tickets/{user}', [ReportController::class, 'exportUserTickets'])
    ->name('reports.export.user-tickets');
```

### Controller Methods
```php
// ReportController.php
public function exportUserReports();
public function exportAllTickets(Request $request);
public function exportUserTickets(User $user, Request $request);
```

---

## 📋 Export Class Features

### Implements Interfaces
Semua export class mengimplementasikan interfaces berikut:

1. **FromCollection** - Data source dari Eloquent collection
2. **WithHeadings** - Custom column headers
3. **WithMapping** - Transform data sebelum export
4. **WithStyles** - Apply cell styling
5. **WithColumnWidths** - Define column widths
6. **ShouldAutoSize** - Auto-size columns
7. **WithTitle** (khusus multi-sheet) - Sheet titles

### Memory Efficiency
- Menggunakan Eloquent queries yang efficient
- Lazy loading untuk large datasets
- Proper relationship eager loading

---

## 💡 Usage Examples

### Admin Export User Reports
```php
// Simple download - no parameters needed
GET /admin/reports/export/users

// Will download: user_reports_2026-01-05_135027.xlsx
```

### Export Filtered Tickets
```php
// Export urgent tickets from specific regional
GET /admin/reports/export/tickets?priority=urgent&regional=Jakarta&date_from=2026-01-01

// Export all open tickets
GET /admin/reports/export/tickets?status=open

// Export tickets by specific user
GET /admin/reports/export/tickets?user_id=5
```

### Export User's Assigned Tickets
```php
// Export all assigned tickets for user ID 3
GET /admin/reports/export/user-tickets/3?type=assigned

// Export solved tickets
GET /admin/reports/export/user-tickets/3?type=solved

// Export inbox tickets
GET /admin/reports/export/user-tickets/3?type=inbox
```

---

## 🔒 Security & Permissions

- ✅ Hanya **Admin** yang dapat mengakses fitur export
- ✅ Routes dilindungi dengan middleware `auth` dan `verified`
- ✅ Validation untuk user ID dan parameters
- ✅ Sanitized output untuk prevent XSS

---

## 📊 Use Cases

### 1. **Monthly Performance Reports**
Admin dapat export user reports di akhir bulan untuk evaluasi performa team.

### 2. **Audit & Compliance**
Export semua tiket dalam periode tertentu untuk audit trail.

### 3. **Data Analysis**
Export data ke Excel untuk analisis lebih lanjut menggunakan Excel features (pivot tables, charts, dll).

### 4. **Backup & Archive**
Regular export sebagai backup data selain database backup.

### 5. **Management Reporting**
Create professional Excel reports untuk management presentation.

---

## 🚀 Future Enhancements

Potential improvements yang bisa ditambahkan:

- [ ] **Multi-sheet exports** - Separate sheets untuk different ticket statuses
- [ ] **Charts & Graphs** - Embedded Excel charts
- [ ] **Custom templates** - Predefined templates dengan company branding
- [ ] **Scheduled exports** - Auto-generate dan email reports
- [ ] **Import functionality** - Bulk import tickets dari Excel
- [ ] **CSV format option** - Alternative format untuk compatibility
- [ ] **PDF export** - Convert Excel to PDF
- [ ] **Custom column selection** - User dapat pilih columns yang mau di-export

---

## 📝 Package Information

**Package:** maatwebsite/excel  
**Version:** ^3.1  
**Documentation:** https://docs.laravel-excel.com  
**GitHub:** https://github.com/SpartnerNL/Laravel-Excel

**Dependencies:**
- PHP: ^8.2
- Laravel: ^12.0
- PhpSpreadsheet: (auto-installed)

---

## 🐛 Troubleshooting

### Error: "Class 'Excel' not found"
```bash
# Pastikan package ter-install
composer require maatwebsite/excel

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### Error: Memory limit exceeded
```php
// Increase memory limit in php.ini
memory_limit = 512M

// Or in export class, use chunking
public function chunkSize(): int
{
    return 1000;
}
```

### Error: File tidak ter-download
```bash
# Pastikan storage writeable
chmod -R 775 storage

# Clear config cache
php artisan config:clear
```

---

## ✅ Testing

### Manual Testing
1. Login sebagai Admin
2. Navigate ke Reports page
3. Test export user reports button
4. Click user card, test dropdown exports
5. Verify Excel file downloads
6. Check Excel formatting dan data

### Automated Testing (Coming soon)
```php
// Example test
public function test_admin_can_export_user_reports()
{
    $response = $this->actingAs($admin)
                     ->get('/admin/reports/export/users');
    
    $response->assertStatus(200);
    $response->assertDownload();
}
```

---

**Last Updated:** January 5, 2026  
**Version:** 1.0.0
