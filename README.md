# Sistem Absensi PHP Native MVC

Aplikasi sistem absensi yang dikonversi dari Laravel ke PHP Native dengan arsitektur MVC.

## Fitur

1. **Authentication**
   - Login/Logout
   - Session management
   - CSRF protection

2. **Dashboard**
   - Statistik peserta
   - Statistik scan
   - Statistik rombongan dan regu

3. **Data Peserta**
   - CRUD peserta
   - Import dari Excel
   - Bulk delete
   - Upload foto

4. **Scan Absensi**
   - Scan QR Code
   - Input manual
   - Real-time status
   - Filter berdasarkan rombongan/regu

5. **Data Scan**
   - Lihat data scan
   - Rekap absensi
   - Hapus scan

6. **Data Export**
   - Export ke PDF
   - Export ke Excel
   - Group by nama absensi

7. **Cetak Kartu**
   - Cetak kartu peserta
   - Filter rombongan/regu
   - QR Code generation

8. **Admin User**
   - CRUD admin user
   - Upload foto profil

## Instalasi

1. Copy folder `absensi` ke htdocs XAMPP
2. Import database dari file `absensidb.sql`
3. Update konfigurasi database di `config/database.php`
4. Pastikan folder `public/image` dan `public/import` writable
5. Copy AdminLTE-2 dari Laravel project ke `public/AdminLTE-2`
6. Copy assets image dari Laravel project ke `public/image`

## Struktur Folder

```
absensi/
├── app/
│   ├── Controllers/     # Controller classes
│   ├── Models/          # Model classes
│   ├── Database.php     # Database connection
│   ├── Auth.php         # Authentication
│   ├── Router.php       # Routing system
│   ├── View.php         # View renderer
│   ├── Request.php      # Request handler
│   ├── Response.php     # Response handler
│   └── helpers.php      # Helper functions
├── config/
│   ├── database.php     # Database config
│   └── app.php          # App config
├── views/               # View files
├── routes/
│   └── web.php          # Route definitions
├── public/              # Public assets
└── index.php            # Entry point
```

## Database

Import file `absensidb.sql` ke database MySQL dengan nama `absensi`.

## Dependencies

- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx

## Catatan

- Untuk fitur Excel import/export, install PhpSpreadsheet via Composer (opsional)
- Untuk fitur PDF export, install DomPDF via Composer (opsional)
- Untuk QR Code generation, install milon/barcode via Composer (opsional)

Jika library tidak terinstall, aplikasi akan menggunakan fallback (CSV untuk Excel, HTML untuk PDF, API online untuk QR Code).

## Default Login

Gunakan user yang sudah ada di database atau buat user baru melalui menu Admin User.

## Support

Untuk pertanyaan atau masalah, silakan hubungi developer.
