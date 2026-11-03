# 🔐 Informasi Akun Kasubag yang Dibuat

## 📋 Data Seeder Kasubag

Setelah menjalankan `KasubagSeeder`, akan dibuat 3 akun kasubag dengan informasi login sebagai berikut:

### 👤 **Kasubag 1 - Kepala Subbagian Tata Usaha dan Kerjasama**
- **Username:** `kasubag1`
- **Password:** `kasubag123`
- **Nama:** Dr. Hj. Fatimah S.Pd, M.Pd
- **Instansi:** BPMP Provinsi Gorontalo
- **Jabatan:** Kepala Subbagian Tata Usaha dan Kerjasama
- **NIP**: 19680315 198803 2 001
- **Email:** kasubag@bpmp.go.id

### 👤 **Kasubag 2 - Kepala Subbagian Program dan Keuangan**
- **Username:** `kasubag2`
- **Password:** `kasubag123`
- **Nama:** Bambang Sutrisno, S.Sos, M.Pd
- **Instansi:** BPMP Provinsi Gorontalo
- **Jabatan:** Kepala Subbagian Program dan Keuangan
- **NIP**: 19651203 198903 1 001
- **Email:** kasubag.program@bpmp.go.id

### 👤 **Kasubag 3 - Kepala Subbagian Umum dan SDM**
- **Username:** `kasubag3`
- **Password:** `kasubag123`
- **Nama:** Dra. Sri Wahyuni, M.A.P
- **Instansi:** BPMP Provinsi Gorontalo
- **Jabatan:** Kepala Subbagian Umum dan SDM
- **NIP**: 19640720 199203 2 001
- **Email:** kasubag.umum@bpmp.go.id

## 🚀 Cara Menjalankan Seeder

### Untuk pertama kali atau fresh database:
```bash
php artisan migrate:fresh --seed
```

### Untuk menjalankan seeder saja:
```bash
php artisan seed:class=KasubagSeeder
```

### Untuk menjalankan semua seeder:
```bash
php artisan db:seed
```

## ⚠️ PENTING!

- **Semua akun kasubag sudah aktif dan disetujui**
- **Password default:** `kasubag123` (disarankan diganti setelah login pertama)
- **Akun ini bisa langsung digunakan untuk login tanpa registrasi**
- **Setiap kasubag dapat mengakses semua fitur approval**

## 🔧 Fitur yang Dapat Diakses Kasubag:

1. **Dashboard Kasubag** - Statistik dan overview pengajuan
2. **Peminjaman Barang** - Approval peminjaman barang dari admin
3. **Permintaan Persediaan** - Approval permintaan persediaan dari admin
4. **Peminjaman Kendaraan** - Approval peminjaman kendaraan dari admin
5. **Peminjaman Aset** - Approval peminjaman aset dari admin

## 📞 Kontak untuk Bantuan Teknis:
Jika ada masalah dengan akun kasubag, silakan hubungi administrator sistem.
