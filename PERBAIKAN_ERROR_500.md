# Perbaikan Error 500 untuk Hosting

## Perubahan yang Dilakukan:

### 1. ✅ Error Reporting Enabled
- File: `index.php`
- Menambahkan error reporting untuk debugging
- Menambahkan try-catch untuk menampilkan error detail

### 2. ✅ Dynamic Base Path Detection
- File: `app/helpers.php`
- File: `app/Router.php`
- File: `app/View.php`
- Fungsi `getBasePath()` sekarang mendeteksi base path secara otomatis dari:
  - Config `app.php` (prioritas pertama)
  - `SCRIPT_NAME` dari server (fallback)

### 3. ✅ Update Config
- File: `config/app.php`
- URL diubah menjadi: `https://kbihu.web.id/absensi` (karena aplikasi di subfolder)

## Langkah Troubleshooting:

### 1. Cek Error Log
Setelah upload, cek file `error.log` di root folder aplikasi untuk melihat error detail.

### 2. Cek Database Connection
Pastikan:
- File `config/database.php` sudah diisi dengan kredensial yang benar
- Database sudah di-import ke hosting
- User database memiliki permission yang cukup

### 3. Cek File Permissions
Pastikan folder berikut memiliki permission 755 atau 777:
- `public/image/users/`
- `public/image/peserta/`
- Folder `app/`, `config/`, `views/` minimal 755

### 4. Cek .htaccess
Pastikan file `.htaccess` di root sudah ada dan berisi:
```
RewriteEngine On
RewriteBase /absensi/
...
```

### 5. Test Koneksi Database
Buat file test `test_db.php` di root:
```php
<?php
$config = require __DIR__ . '/config/database.php';
try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}",
        $config['username'],
        $config['password']
    );
    echo "Database connection: OK";
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
```

## File yang Perlu Di-upload ke Hosting:

1. ✅ Semua file aplikasi
2. ✅ Pastikan struktur folder tetap sama
3. ✅ Pastikan `config/database.php` dan `config/app.php` sudah di-update
4. ✅ Pastikan `.htaccess` ada di root folder

## Setelah Upload:

1. Akses: `https://kbihu.web.id/absensi/login`
2. Jika masih error 500, cek:
   - Error log di hosting panel
   - File `error.log` di root aplikasi
   - PHP error log di hosting
