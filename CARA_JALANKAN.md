# Cara Menjalankan Aplikasi Absensi

## Langkah 1: Pastikan XAMPP Running
1. Buka XAMPP Control Panel
2. Start **Apache** dan **MySQL**
3. Pastikan keduanya berstatus "Running" (hijau)

## Langkah 2: Buat Database
1. Buka browser, akses: `http://localhost/phpmyadmin`
2. Klik **New** untuk membuat database baru
3. Nama database: `absensi`
4. Collation: `utf8mb4_general_ci`
5. Klik **Create**

## Langkah 3: Import Database
1. Di phpMyAdmin, pilih database `absensi`
2. Klik tab **Import**
3. Klik **Choose File**
4. Pilih file: `/Users/admin/Documents/absensinew/absensidb.sql`
5. Klik **Go** untuk import

## Langkah 4: Konfigurasi Database
Edit file: `/Applications/XAMPP/xamppfiles/htdocs/absensi/config/database.php`

```php
return [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',  // Kosongkan jika tidak ada password
    'database' => 'absensi',
    'charset' => 'utf8mb4'
];
```

**Catatan:** Jika MySQL Anda punya password, isi di field `password`.

## Langkah 5: Copy Assets (Penting!)
Jalankan di Terminal:

```bash
# Copy AdminLTE
cp -r /Users/admin/Documents/absensinew/public/AdminLTE-2 /Applications/XAMPP/xamppfiles/htdocs/absensi/public/

# Copy images (jika ada)
cp -r /Users/admin/Documents/absensinew/public/image/* /Applications/XAMPP/xamppfiles/htdocs/absensi/public/image/ 2>/dev/null || echo "Image folder akan dibuat otomatis"

# Copy import template
cp /Users/admin/Documents/absensinew/public/import/Format\ Import\ Peserta.xlsx /Applications/XAMPP/xamppfiles/htdocs/absensi/public/import/ 2>/dev/null || echo "Import folder akan dibuat otomatis"
```

## Langkah 6: Set Permissions
```bash
chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/absensi/public/image
chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/absensi/public/import
```

## Langkah 7: Akses Aplikasi
Buka browser dan akses:
```
http://localhost/absensi/
```

Aplikasi akan otomatis redirect ke halaman login.

## Langkah 8: Login
Gunakan user yang sudah ada di database atau buat user baru:
- Email: `admin@admin.com` (atau user lain yang ada di database)
- Password: (cek di database atau buat user baru)

## Troubleshooting

### Error 500
1. Cek error log: `/Applications/XAMPP/xamppfiles/logs/error_log`
2. Pastikan semua file ada
3. Pastikan database sudah diimport
4. Cek konfigurasi database

### Error Database Connection
- Pastikan MySQL running
- Cek username/password di `config/database.php`
- Pastikan database `absensi` sudah dibuat dan diimport

### Error 404
- Pastikan file `.htaccess` ada
- Cek apakah mod_rewrite enabled di Apache
- Cek konfigurasi Apache `AllowOverride All`

### Halaman Kosong
- Cek error log PHP
- Pastikan `display_errors` enabled di `php.ini`
- Cek apakah semua class Controller dan Model ada

## Catatan Penting
- PHP version minimal 7.4
- Extension PDO dan PDO_MySQL harus enabled
- Pastikan folder `public/image` dan `public/import` writable
