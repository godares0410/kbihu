# Fix CSS Tidak Terload

## Masalah
CSS tidak terload, halaman tampil tanpa style.

## Solusi yang Sudah Diterapkan

1. ✅ AdminLTE-2 sudah di-copy ke `public/AdminLTE-2/`
2. ✅ File CSS sudah ada di `public/AdminLTE-2/dist/css/AdminLTE.min.css`
3. ✅ Path asset() sudah benar: `/absensi/public/AdminLTE-2/...`
4. ✅ .htaccess sudah diperbaiki untuk allow direct access ke file static

## Test CSS

Akses file test untuk memastikan CSS bisa diakses:
```
http://localhost/absensi/test_asset.php
```

Atau langsung test CSS file:
```
http://localhost/absensi/public/AdminLTE-2/dist/css/AdminLTE.min.css
```

Jika file CSS bisa dibuka (menampilkan kode CSS), berarti file bisa diakses.

## Jika Masih Tidak Berfungsi

### 1. Clear Browser Cache
- Tekan `Ctrl+Shift+R` (Windows/Linux) atau `Cmd+Shift+R` (Mac)
- Atau buka Developer Tools (F12) > Network > Disable cache

### 2. Cek Browser Console
- Buka Developer Tools (F12)
- Tab Console: cek apakah ada error
- Tab Network: cek apakah file CSS return 404 atau error lain

### 3. Cek File Permissions
```bash
chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/absensi/public/AdminLTE-2
```

### 4. Cek Apache Configuration
Pastikan di `httpd.conf` atau virtual host:
```apache
AllowOverride All
```

### 5. Alternative: Gunakan Path Absolute
Jika masih bermasalah, bisa ubah helper function `asset()` untuk return absolute URL.

## Verifikasi

1. Buka halaman login: `http://localhost/absensi/login`
2. View page source (Ctrl+U)
3. Cek link CSS, contoh:
   ```html
   <link rel="stylesheet" href="/absensi/public/AdminLTE-2/dist/css/AdminLTE.min.css">
   ```
4. Klik link CSS tersebut, harusnya menampilkan kode CSS
5. Jika 404, berarti path salah atau file tidak ada
6. Jika 200 tapi tidak ada style, berarti ada masalah di browser cache atau CSS conflict
