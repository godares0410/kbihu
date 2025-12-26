# Panduan Instalasi Aplikasi Absensi

## Langkah-langkah Instalasi

### 1. Pastikan XAMPP Berjalan
- Pastikan Apache dan MySQL sudah running di XAMPP Control Panel

### 2. Import Database
```sql
-- Buka phpMyAdmin (http://localhost/phpmyadmin)
-- Buat database baru dengan nama: absensi
-- Import file absensidb.sql dari folder /Users/admin/Documents/absensinew/
```

### 3. Konfigurasi Database
Edit file `config/database.php`:
```php
return [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',  // Sesuaikan dengan password MySQL Anda
    'database' => 'absensi',
    'charset' => 'utf8mb4'
];
```

### 4. Copy Assets
```bash
# Copy AdminLTE dari project Laravel
cp -r /Users/admin/Documents/absensinew/public/AdminLTE-2 /Applications/XAMPP/xamppfiles/htdocs/absensi/public/

# Copy images (jika ada)
cp -r /Users/admin/Documents/absensinew/public/image/* /Applications/XAMPP/xamppfiles/htdocs/absensi/public/image/
```

### 5. Set Permissions
```bash
chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/absensi/public/image
chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/absensi/public/import
```

### 6. Akses Aplikasi
Buka browser dan akses:
```
http://localhost/absensi/
```

Aplikasi akan otomatis redirect ke halaman login.

### 7. Login Default
Gunakan user yang sudah ada di database atau buat user baru melalui menu Admin User setelah login.

## Troubleshooting

### Error 500
1. Cek error log Apache: `/Applications/XAMPP/xamppfiles/logs/error_log`
2. Pastikan semua file ada di folder yang benar
3. Pastikan database sudah diimport
4. Cek konfigurasi database di `config/database.php`

### Error Database Connection
1. Pastikan MySQL running
2. Cek username dan password di `config/database.php`
3. Pastikan database `absensi` sudah dibuat

### Error Class Not Found
1. Pastikan file autoload.php sudah benar
2. Cek apakah semua file Controller dan Model ada

### Error 404
1. Pastikan .htaccess sudah ada
2. Cek apakah mod_rewrite enabled di Apache
3. Cek konfigurasi Apache AllowOverride

## Catatan
- Pastikan PHP version >= 7.4
- Pastikan extension PDO dan PDO_MySQL enabled
- Untuk fitur Excel/PDF, install library tambahan (opsional)
