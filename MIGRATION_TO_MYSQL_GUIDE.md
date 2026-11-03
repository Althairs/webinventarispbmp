# Panduan Migrasi Database dari SQLite ke MySQL

Dokumen ini menjelaskan langkah-langkah lengkap untuk migrasi database aplikasi Laravel dari SQLite ke MySQL.

## 📋 Daftar Isi

1. [Persiapan](#persiapan)
2. [Langkah-langkah Migrasi](#langkah-langkah-migrasi)
3. [Verifikasi](#verifikasi)
4. [Troubleshooting](#troubleshooting)
5. [Ringkasan Perubahan](#ringkasan-perubahan)

## 🔧 Persiapan

### 1. Backup Data

**PENTING:** Sebelum memulai, backup database SQLite Anda:

```bash
# Backup file SQLite
cp database/database.sqlite database/database.sqlite.backup
```

### 2. Install MySQL

Pastikan MySQL/MariaDB sudah terinstall di sistem Anda. Untuk Laragon, MySQL biasanya sudah terinstall.

### 3. Buat Database MySQL

Buat database baru di MySQL:

```sql
CREATE DATABASE nama_database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 📝 Langkah-langkah Migrasi

### Step 1: Update Konfigurasi Database

#### A. Update file `.env`

Ubah konfigurasi database dari SQLite ke MySQL:

```env
# Sebelum (SQLite)
DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite

# Sesudah (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

#### B. Pastikan konfigurasi di `config/database.php` sudah benar

Cek bagian MySQL configuration:

```php
'mysql' => [
    'driver' => 'mysql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    // ...
],
```

### Step 2: Buat Tabel di MySQL

Jalankan migration untuk membuat semua tabel dengan struktur yang kompatibel dengan MySQL:

```bash
php artisan migrate --path=database/migrations/2025_10_30_000001_migrate_to_mysql_all_tables.php
```

Atau jika ingin menjalankan semua migration:

```bash
php artisan migrate:fresh
```

**Catatan:** Migration `2025_10_30_000001_migrate_to_mysql_all_tables.php` sudah dibuat khusus untuk MySQL dengan:
- `bigIncrements` untuk primary key
- `unsignedBigInteger` untuk foreign key
- Index yang tepat untuk performa
- Foreign key constraints yang benar

### Step 3: Transfer Data dari SQLite ke MySQL

Jalankan script transfer data:

```bash
php database/transfer_sqlite_to_mysql.php
```

Script ini akan:
- ✅ Membaca semua data dari SQLite
- ✅ Memindahkan data ke MySQL dengan urutan yang benar (menjaga foreign key)
- ✅ Menampilkan progress dan statistik
- ✅ Memverifikasi jumlah record

**Output yang diharapkan:**
```
========================================
  SQLite to MySQL Data Transfer Tool
========================================

✅ Koneksi MySQL berhasil
✅ Koneksi SQLite berhasil

Mulai transfer data...
----------------------------------------

📦 Transfer tabel utama...
  ✅ users: 10 record berhasil ditransfer
  ✅ persediaan: 25 record berhasil ditransfer
  ✅ aset_tetap: 15 record berhasil ditransfer
...
```

### Step 4: Test Aplikasi

Setelah transfer selesai, test aplikasi untuk memastikan semua berjalan:

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Test koneksi database
php artisan tinker
>>> DB::table('users')->count()
```

## ✅ Verifikasi

### 1. Verifikasi Jumlah Data

Bandingkan jumlah record antara SQLite dan MySQL:

**SQLite:**
```bash
php artisan tinker
>>> Config::set('database.default', 'sqlite')
>>> DB::table('users')->count()
```

**MySQL:**
```bash
php artisan tinker
>>> Config::set('database.default', 'mysql')
>>> DB::table('users')->count()
```

### 2. Test Fitur Aplikasi

Uji fitur-fitur utama:
- ✅ Login/Register
- ✅ CRUD Persediaan
- ✅ CRUD Aset Tetap
- ✅ Transaksi Masuk/Keluar
- ✅ Mutasi Barang
- ✅ Notifikasi
- ✅ Permintaan/Peminjaman

### 3. Cek Foreign Key Constraints

Pastikan semua foreign key berfungsi dengan baik:

```sql
-- Di MySQL
SHOW CREATE TABLE transaksi_masuk_persediaan;
```

## 🔍 Troubleshooting

### Error: Foreign Key Constraint Fails

**Problem:** Data gagal di-insert karena foreign key constraint

**Solution:**
1. Pastikan tabel `users` sudah di-transfer terlebih dahulu
2. Check apakah ada data di SQLite yang reference ke ID yang tidak ada
3. Jalankan script transfer lagi (script akan truncate tabel terlebih dahulu)

### Error: Data Type Mismatch

**Problem:** Tipe data tidak sesuai antara SQLite dan MySQL

**Solution:**
- Migration sudah dibuat dengan tipe data yang tepat untuk MySQL
- Jika masih error, cek kolom yang bermasalah di migration file

### Error: Connection Refused

**Problem:** Tidak bisa connect ke MySQL

**Solution:**
```bash
# Cek MySQL service
# Windows (Laragon): Pastikan MySQL sudah running di Laragon
# Linux: sudo systemctl status mysql
# Mac: brew services list

# Test koneksi
mysql -u root -p -h 127.0.0.1
```

### Error: Table Already Exists

**Problem:** Tabel sudah ada di MySQL

**Solution:**
```bash
# Hapus semua tabel
php artisan migrate:fresh

# Atau hapus manual
DROP DATABASE nama_database;
CREATE DATABASE nama_database;
```

## 📊 Ringkasan Perubahan

### Perubahan Tipe Data SQLite → MySQL

| SQLite | MySQL | Keterangan |
|--------|-------|------------|
| `INTEGER PRIMARY KEY` | `BIGINT UNSIGNED AUTO_INCREMENT` | Primary Key |
| `INTEGER` | `BIGINT` | Foreign Key |
| `TEXT` | `VARCHAR(n)` atau `TEXT` | String |
| `REAL` | `DECIMAL(15,2)` | Decimal |
| `DATETIME` | `DATETIME` atau `TIMESTAMP` | DateTime |

### Tabel yang Dimigrasikan

✅ **Tabel Utama:**
- `users`
- `persediaan`
- `aset_tetap`

✅ **Tabel Request:**
- `request_models`
- `permintaan_persediaans`
- `peminjaman_kendaraans`
- `peminjaman_asets`

✅ **Tabel Transaksi:**
- `transaksi_masuk_persediaan`
- `transaksi_masuk_aset_tetap`
- `transaksi_keluar_persediaan`
- `transaksi_keluar_aset_tetap`

✅ **Tabel Lainnya:**
- `mutasi_barang`
- `surat_persetujuan`
- `notifikasis`

✅ **Tabel Sistem:**
- `sessions`
- `cache`
- `password_reset_tokens`
- `jobs`
- `failed_jobs`

### Foreign Key Relationships

Semua foreign key relationships tetap dipertahankan:
- `user_id` → `users.id`
- `persediaan_id` → `persediaan.id`
- `aset_tetap_id` → `aset_tetap.id`
- `request_id` → various request tables
- `surat_persetujuan_id` → `surat_persetujuan.id`

## 🎯 Status Kompatibilitas Tabel

| Tabel | Status | Notes |
|-------|--------|-------|
| users | ✅ Kompatibel | Semua field ter-migrate |
| persediaan | ✅ Kompatibel | Decimal dengan presisi benar |
| aset_tetap | ✅ Kompatibel | Enum untuk kondisi |
| request_models | ✅ Kompatibel | Foreign keys valid |
| permintaan_persediaans | ✅ Kompatibel | - |
| peminjaman_kendaraans | ✅ Kompatibel | Enum untuk status |
| peminjaman_asets | ✅ Kompatibel | - |
| transaksi_masuk_persediaan | ✅ Kompatibel | Foreign keys valid |
| transaksi_masuk_aset_tetap | ✅ Kompatibel | Foreign keys valid |
| transaksi_keluar_persediaan | ✅ Kompatibel | Foreign keys valid |
| transaksi_keluar_aset_tetap | ✅ Kompatibel | Foreign keys valid |
| mutasi_barang | ✅ Kompatibel | Foreign keys valid |
| surat_persetujuan | ✅ Kompatibel | Enum untuk jenis surat |
| notifikasis | ✅ Kompatibel | Foreign keys valid |

## 🚀 Testing

Setelah migrasi selesai, lakukan testing:

```bash
# 1. Test koneksi database
php artisan tinker
>>> DB::connection()->getPdo()

# 2. Test query sederhana
>>> \App\Models\User::count()
>>> \App\Models\Persediaan::count()
>>> \App\Models\AsetTetap::count()

# 3. Test relasi
>>> $user = \App\Models\User::first()
>>> $user->peminjamanAsets

# 4. Test aplikasi web
php artisan serve
# Buka browser dan test fitur-fitur utama
```

## 📝 Checklist Migrasi

- [ ] Backup database SQLite
- [ ] Install/Setup MySQL
- [ ] Buat database MySQL baru
- [ ] Update `.env` file
- [ ] Jalankan migration MySQL
- [ ] Jalankan script transfer data
- [ ] Verifikasi jumlah record
- [ ] Test fitur aplikasi
- [ ] Clear cache
- [ ] Update dokumentasi tim

## ⚠️ Catatan Penting

1. **Jangan hapus file SQLite** sampai Anda yakin 100% bahwa migrasi berhasil
2. **Backup terlebih dahulu** sebelum melakukan perubahan apapun
3. **Test semua fitur** setelah migrasi selesai
4. **Update dokumentasi** jika ada perubahan struktur

## 📞 Support

Jika mengalami masalah:
1. Cek log Laravel: `storage/logs/laravel.log`
2. Cek log MySQL: `storage/logs/` (jika ada)
3. Pastikan semua dependency sudah ter-install
4. Pastikan konfigurasi `.env` sudah benar

---

**Last Updated:** 2025-10-30
**Migration Version:** 2025_10_30_000001

