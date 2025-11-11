# Profile System - Agent Dashboard

## 📊 Overview
Sistem Profile memungkinkan agent untuk melihat dan mengedit informasi personal mereka, serta mengubah password. Semua data tersimpan di database dan berlaku untuk semua akun.

## ✨ Fitur Utama

### 1. **Personal Information**
Informasi personal agent yang tersimpan di database:
- **Name**: Nama lengkap agent
- **Campaign**: Campaign yang ditangani (contoh: BESFIX)
- **Site**: Lokasi site (contoh: Bandung)
- **Username**: Username unik agent
- **Email**: Email agent (unique)
- **Phone**: Nomor telepon agent

#### Cara Menggunakan:
1. Klik icon Profile di sidebar atau "My Profile" di dropdown user
2. Lihat informasi personal Anda
3. Klik tombol "Edit Profile" untuk mengubah data
4. Isi form dengan data baru
5. Klik "Save Changes" untuk menyimpan
6. Data akan tersimpan di database

### 2. **Change Password**
Fitur untuk mengubah password:
- **Current Password**: Password saat ini (untuk validasi)
- **New Password**: Password baru yang diinginkan
- **Confirm New Password**: Konfirmasi password baru

#### Cara Menggunakan:
1. Masukkan password saat ini
2. Masukkan password baru
3. Konfirmasi password baru
4. Klik "Update Password"
5. Password akan diupdate di database

#### Validasi:
- Current password harus benar
- New password harus memenuhi kriteria keamanan
- Confirmation password harus sama dengan new password

### 3. **Personalization**
Preferensi tampilan (coming soon):
- Dark Mode
- Compact Sidebar
- Table Density

## 🗄️ Database Structure

### Migration: `add_profile_fields_to_users_table`
Menambahkan field baru ke tabel `users`:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('campaign')->nullable();
    $table->string('site')->nullable();
    $table->string('username')->nullable();
    $table->string('phone')->nullable();
});
```

### User Model
Field yang dapat diisi (fillable):
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'campaign',
    'site',
    'username',
    'phone',
];
```

## 🔧 Technical Details

### Routes
```php
// Display profile
Route::get('/agent/profile', [AgentProfileController::class, 'show'])
    ->middleware('role:agent')
    ->name('agent.profile');

// Update profile information
Route::patch('/agent/profile', [AgentProfileController::class, 'update'])
    ->middleware('role:agent')
    ->name('agent.profile.update');

// Update password
Route::patch('/agent/profile/password', [AgentProfileController::class, 'updatePassword'])
    ->middleware('role:agent')
    ->name('agent.profile.password');
```

### Controller Methods

#### 1. Show Profile
```php
public function show()
{
    return view('agent.profile', [
        'user' => Auth::user()
    ]);
}
```

#### 2. Update Profile
```php
public function update(Request $request)
{
    $user = Auth::user();
    
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'campaign' => ['nullable', 'string', 'max:255'],
        'site' => ['nullable', 'string', 'max:255'],
        'username' => ['nullable', 'string', 'max:255'],
        'phone' => ['nullable', 'string', 'max:20'],
    ]);
    
    $user->update($validated);
    
    return redirect()->route('agent.profile')->with('success', 'Profile updated successfully!');
}
```

#### 3. Update Password
```php
public function updatePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => ['required', 'current_password'],
        'password' => ['required', Password::defaults(), 'confirmed'],
    ]);
    
    $user = Auth::user();
    $user->update([
        'password' => Hash::make($validated['password']),
    ]);
    
    return redirect()->route('agent.profile')->with('success', 'Password updated successfully!');
}
```

## 🎯 Use Cases

### Skenario 1: Update Personal Information
**Kebutuhan**: Agent ingin update informasi campaign dan site
1. Buka halaman profile
2. Klik "Edit Profile"
3. Ubah field Campaign menjadi "BESFIX"
4. Ubah field Site menjadi "Bandung"
5. Klik "Save Changes"
6. Data tersimpan di database dan dapat dilihat oleh admin/team leader

### Skenario 2: Change Password
**Kebutuhan**: Agent ingin mengubah password untuk keamanan
1. Buka halaman profile
2. Scroll ke section "Change Password"
3. Masukkan password lama
4. Masukkan password baru
5. Konfirmasi password baru
6. Klik "Update Password"
7. Password berhasil diubah

### Skenario 3: View Profile from Dashboard
**Kebutuhan**: Agent ingin melihat informasi profile
1. Dari dashboard, klik icon user di sidebar
2. Atau klik avatar di header → "My Profile"
3. Halaman profile terbuka
4. Lihat semua informasi personal

## 🚀 Future Enhancements

### Profile Picture Upload
```php
// Add to migration
$table->string('avatar')->nullable();

// Add to controller
public function updateAvatar(Request $request)
{
    $validated = $request->validate([
        'avatar' => ['required', 'image', 'max:2048'],
    ]);
    
    $path = $request->file('avatar')->store('avatars', 'public');
    
    Auth::user()->update(['avatar' => $path]);
    
    return redirect()->route('agent.profile')->with('success', 'Avatar updated!');
}
```

### Activity Log
Track profile changes:
```php
// Create activity_logs table
Schema::create('activity_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('action');
    $table->text('description')->nullable();
    $table->timestamps();
});

// Log in controller
ActivityLog::create([
    'user_id' => Auth::id(),
    'action' => 'profile_updated',
    'description' => 'Updated personal information',
]);
```

### Email Verification
When email is changed:
```php
if ($user->email !== $validated['email']) {
    $user->email_verified_at = null;
    $user->sendEmailVerificationNotification();
}
```

### Two-Factor Authentication
Add 2FA for security:
```php
$table->string('two_factor_secret')->nullable();
$table->text('two_factor_recovery_codes')->nullable();
```

## 📝 Validation Rules

### Profile Update
- **name**: Required, string, max 255 characters
- **email**: Required, valid email, unique (except current user), max 255 characters
- **campaign**: Optional, string, max 255 characters
- **site**: Optional, string, max 255 characters
- **username**: Optional, string, max 255 characters
- **phone**: Optional, string, max 20 characters

### Password Update
- **current_password**: Required, must match current password
- **password**: Required, must meet password requirements, confirmed
- **password_confirmation**: Required, must match password

## 🔒 Security

### Current Implementation
- ✅ CSRF Protection on all forms
- ✅ Password hashing with bcrypt
- ✅ Current password validation before change
- ✅ Role-based access control (middleware)
- ✅ Unique email validation
- ✅ SQL injection protection (Eloquent ORM)

### Recommendations
- [ ] Rate limiting on password change
- [ ] Email notification on profile/password change
- [ ] Password history (prevent reusing old passwords)
- [ ] Session invalidation after password change
- [ ] Audit log for profile changes

## 🎨 UI Components

### Profile Hero
```html
<div class="profile-hero">
    <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    <div class="identity">
        <div class="name">{{ Auth::user()->name }}</div>
        <div class="role">Agent</div>
    </div>
</div>
```

### Edit Toggle
```javascript
function toggleEdit() {
    const infoDisplay = document.getElementById('infoDisplay');
    const editForm = document.getElementById('editForm');
    
    if (editForm.style.display === 'none') {
        infoDisplay.style.display = 'none';
        editForm.style.display = 'block';
    } else {
        infoDisplay.style.display = 'block';
        editForm.style.display = 'none';
    }
}
```

## 📞 Testing

### Manual Testing Steps
1. **View Profile**
   - Login sebagai agent
   - Klik icon profile
   - Verify semua data tampil dengan benar

2. **Edit Profile**
   - Klik "Edit Profile"
   - Ubah beberapa field
   - Klik "Save Changes"
   - Verify data tersimpan di database
   - Refresh page dan verify data masih ada

3. **Change Password**
   - Masukkan current password yang salah → harus error
   - Masukkan password baru yang tidak match → harus error
   - Masukkan data yang benar → harus berhasil
   - Logout dan login dengan password baru → harus berhasil

4. **Validation**
   - Submit form kosong → harus error
   - Submit email yang sudah dipakai user lain → harus error
   - Submit phone dengan format salah → harus error

## 🐛 Troubleshooting

### Issue: "Column not found: campaign"
**Solution**: Run migration
```bash
php artisan migrate
```

### Issue: "Mass assignment error"
**Solution**: Add fields to User model fillable array

### Issue: "Current password is incorrect"
**Solution**: Verify password is correct, check password hashing

### Issue: "Email already taken"
**Solution**: Use different email or check if it's your own email

## 📊 Database Queries

### Get user profile
```php
$user = User::find(Auth::id());
```

### Update profile
```php
$user->update([
    'name' => 'New Name',
    'campaign' => 'BESFIX',
    'site' => 'Bandung',
]);
```

### Update password
```php
$user->update([
    'password' => Hash::make('new_password'),
]);
```

### Check password
```php
if (Hash::check('password', $user->password)) {
    // Password is correct
}
```
