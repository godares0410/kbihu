# Konfigurasi Database untuk Hosting hPanel (Hostinger)

## Langkah-langkah:

### 1. Buat Database di hPanel
- Login ke hPanel
- Buka **MySQL Databases** atau **Database**
- Klik **Create Database**
- Buat database baru (contoh: `absensi`)
- Buat user database baru
- Berikan semua privileges ke user tersebut
- Catat: **Host**, **Username**, **Password**, dan **Database Name**

### 2. Edit File `config/database.php`

```php
<?php
return [
    'host' => 'localhost',  // Biasanya 'localhost' untuk hPanel
    'username' => 'username_database',  // Username dari hPanel
    'password' => 'password_database',  // Password dari hPanel
    'database' => 'nama_database',  // Nama database dari hPanel
    'charset' => 'utf8mb4'
];
```

### 3. Informasi yang Diperlukan dari hPanel:

#### Host:
- **Biasanya**: `localhost`
- **Jika berbeda**: Cek di hPanel → MySQL Databases → Host (bisa jadi `localhost` atau IP tertentu)

#### Username:
- Format biasanya: `username_dbname` atau `u123456789_absensi`
- Contoh: Jika username hPanel Anda `myuser` dan database `absensi`, maka username bisa `myuser_absensi`

#### Password:
- Password yang Anda buat saat membuat database user di hPanel

#### Database Name:
- Format biasanya: `username_dbname` atau `u123456789_absensi`
- Contoh: `myuser_absensi` atau `u123456789_absensi`

### 4. Contoh Konfigurasi:

**Jika di hPanel Anda melihat:**
- Database Name: `u123456789_absensi`
- Database User: `u123456789_dbuser`
- Host: `localhost`

**Maka konfigurasi di `config/database.php`:**
```php
<?php
return [
    'host' => 'localhost',
    'username' => 'u123456789_dbuser',
    'password' => 'password_yang_anda_buat',
    'database' => 'u123456789_absensi',
    'charset' => 'utf8mb4'
];
```

### 5. Import Database:
- Export database dari localhost (phpMyAdmin)
- Import ke database di hPanel melalui phpMyAdmin hPanel

### 6. Upload File:
- Upload semua file aplikasi ke folder `public_html` atau `htdocs` di hPanel
- Pastikan folder `public/image/users/` dan `public/image/peserta/` memiliki permission 755 atau 777

### 7. Test Koneksi:
- Akses aplikasi melalui domain Anda
- Jika ada error, cek kembali konfigurasi database
