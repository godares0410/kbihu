# Solusi CSS Tidak Terload

## Status
✅ AdminLTE-2 sudah di-copy
✅ File CSS sudah ada
✅ Path sudah benar

## Langkah Perbaikan

### 1. Clear Browser Cache
Tekan `Ctrl+Shift+R` (Windows) atau `Cmd+Shift+R` (Mac) untuk hard refresh.

### 2. Test CSS File Langsung
Buka di browser:
```
http://localhost/absensi/public/AdminLTE-2/dist/css/AdminLTE.min.css
```

Jika menampilkan kode CSS, berarti file bisa diakses.

### 3. Cek Browser Console
1. Buka Developer Tools (F12)
2. Tab **Network**
3. Reload halaman
4. Cek apakah file CSS return:
   - ✅ **200 OK** = File bisa diakses
   - ❌ **404 Not Found** = File tidak ditemukan
   - ❌ **403 Forbidden** = Permission issue

### 4. Jika Masih 404
Coba akses dengan path lengkap:
```
http://localhost/absensi/public/AdminLTE-2/dist/css/AdminLTE.min.css
```

### 5. Alternative: Ubah Base Path
Jika masih bermasalah, edit `app/helpers.php`:
```php
function asset($path) {
    $path = ltrim($path, '/');
    // Gunakan absolute path jika perlu
    return 'http://' . $_SERVER['HTTP_HOST'] . '/absensi/public/' . $path;
}
```

### 6. Restart Apache
Setelah perubahan .htaccess, restart Apache:
- XAMPP Control Panel > Stop Apache > Start Apache

## Verifikasi
1. Buka: `http://localhost/absensi/login`
2. View Source (Ctrl+U)
3. Cari tag `<link rel="stylesheet"`
4. Klik URL CSS tersebut
5. Harusnya menampilkan kode CSS

Jika semua sudah benar tapi masih tidak ada style, kemungkinan:
- Browser cache (clear cache)
- CSS conflict
- File CSS corrupt (re-copy AdminLTE-2)
