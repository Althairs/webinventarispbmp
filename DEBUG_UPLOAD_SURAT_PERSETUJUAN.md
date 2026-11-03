# Dokumentasi Debugging Upload Surat Persetujuan

## Masalah yang Ditemukan

### 1. Foreign Key Constraint yang Salah
Tabel `surat_persetujuan` memiliki foreign key constraint untuk `request_id` yang hanya mengarah ke `request_models`. Padahal:
- **SPA** (Peminjaman Aset) → `request_id` harus ada di tabel `peminjaman_asets`
- **SPK** (Peminjaman Kendaraan) → `request_id` harus ada di tabel `peminjaman_kendaraans`
- **SPB** (Peminjaman Barang) → `request_id` harus ada di tabel `request_models`
- **SPP** (Permintaan Persediaan) → `request_id` harus ada di tabel `permintaan_persediaans`

Karena `request_id` bisa merujuk ke 4 tabel berbeda, foreign key constraint di database tidak bisa digunakan. Validasi sudah dilakukan di aplikasi melalui method `validateRequestId()` di controller.

### 2. Error FOREIGN KEY Constraint Failed
Saat mencoba upload surat persetujuan untuk SPA dengan `request_id` yang valid di `peminjaman_asets`, database SQLite menolak karena foreign key constraint hanya memeriksa apakah `request_id` ada di `request_models`.

## Solusi

### 1. Hapus Foreign Key Constraint
Jalankan migration untuk menghapus foreign key constraint yang salah:

```bash
php artisan migrate
```

Migration akan:
- Menghapus foreign key constraint untuk `request_id` dari tabel `surat_persetujuan`
- Mempertahankan foreign key untuk `uploaded_by` (ke tabel `users`)
- Validasi `request_id` tetap dilakukan di aplikasi (sudah ada di controller)

### 2. Tools untuk Debugging

#### A. Cek Validitas Request ID
```bash
# Cek untuk SPA (Peminjaman Aset)
php artisan check:request-id SPA 1

# Cek untuk SPK (Peminjaman Kendaraan)
php artisan check:request-id SPK 1

# Cek untuk SPB (Peminjaman Barang)
php artisan check:request-id SPB 1

# Cek untuk SPP (Permintaan Persediaan)
php artisan check:request-id SPP 1
```

#### B. Cek Struktur Foreign Key di Database
```bash
# Cek untuk tabel tertentu
php artisan check:foreign-keys surat_persetujuan

# Cek untuk semua tabel penting
php artisan check:foreign-keys
```

## Cara Menggunakan Script

### 1. Sebelum Upload Surat Persetujuan
```bash
# Pastikan request_id valid
php artisan check:request-id SPA [ID_REQUEST]
```

Jika valid, akan menampilkan:
- ✅ Request ID valid
- Informasi detail request
- Status apakah sudah ada surat persetujuan

Jika tidak valid, akan menampilkan:
- ❌ Request ID tidak valid
- Daftar ID yang tersedia

### 2. Setelah Error Upload
```bash
# Cek struktur foreign key
php artisan check:foreign-keys surat_persetujuan
```

Pastikan tidak ada foreign key constraint untuk `request_id`.

### 3. Melihat Log Laravel
```bash
# Melihat log terkait upload
Get-Content storage/logs/laravel.log | Select-String -Pattern "Upload surat persetujuan|SPA|Failed to" -Context 5
```

## File yang Dibuat

1. **app/Console/Commands/CheckRequestId.php**
   - Command untuk mengecek validitas request_id
   - Usage: `php artisan check:request-id {jenis_surat} {request_id}`

2. **app/Console/Commands/CheckDatabaseForeignKeys.php**
   - Command untuk memeriksa struktur foreign key
   - Usage: `php artisan check:foreign-keys {table?}`

3. **database/migrations/2025_11_03_023859_remove_invalid_foreign_key_from_surat_persetujuan.php**
   - Migration untuk menghapus foreign key constraint yang salah

## Catatan Penting

1. **Foreign Key Constraint untuk request_id dihapus** karena tidak mungkin membuat constraint yang dinamis (bisa merujuk ke 4 tabel berbeda).

2. **Validasi dilakukan di aplikasi** melalui method `validateRequestId()` di `SuratPersetujuanController`.

3. **File tetap tersimpan di storage** meskipun ada error di database - perlu cleanup manual jika diperlukan.

4. **Untuk production**, pastikan:
   - Migration sudah dijalankan
   - Foreign key constraint untuk request_id sudah dihapus
   - Validasi di aplikasi bekerja dengan benar

