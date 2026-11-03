# Fitur Import Excel untuk Data Aset Tetap

## Deskripsi
Fitur ini memungkinkan admin untuk mengimport data aset tetap dari file Excel (.xlsx/.xls) ke dalam sistem.

## Cara Penggunaan

### 1. Akses Fitur Import
- Login sebagai admin
- Buka halaman "Data Aset Tetap"
- Klik tombol "Import Excel" (tombol hijau)

### 2. Download Template
- Klik "Download Template" untuk mendapatkan file Excel template
- Template berisi contoh format data yang benar

### 3. Format Excel yang Diperlukan

**Kolom Wajib (harus diisi):**
- **A: Kode Barang** - Kode unik aset (maksimal 50 karakter)
- **B: Nama Barang** - Nama aset (maksimal 100 karakter)
- **C: Merek** - Merek aset (maksimal 100 karakter)
- **D: Kategori** - Kategori aset (maksimal 100 karakter)
- **E: Kondisi** - Harus salah satu dari: `baik`, `rusak_ringan`, `rusak_berat`
- **F: Lokasi** - Lokasi aset (maksimal 100 karakter)
- **I: Jumlah** - Jumlah aset (minimal 1)

**Kolom Opsional:**
- **G: Nilai Perolehan** - Nilai dalam angka (bisa kosong)
- **H: Tanggal Peroleh** - Format: YYYY-MM-DD (contoh: 2024-01-15)
- **J: NUP** - Nomor Urut Penerimaan (maksimal 50 karakter)

### 4. Upload File
- Pilih file Excel yang sudah diisi
- Klik "Import Data"
- Sistem akan memproses dan menampilkan hasil

## Validasi Data

### Aturan Validasi:
1. **Kode Barang**: Wajib, string, maksimal 50 karakter
2. **Nama Barang**: Wajib, string, maksimal 100 karakter
3. **Merek**: Wajib, string, maksimal 100 karakter
4. **Kategori**: Wajib, string, maksimal 100 karakter
5. **Kondisi**: Wajib, harus salah satu dari: `baik`, `rusak_ringan`, `rusak_berat`
6. **Lokasi**: Wajib, string, maksimal 100 karakter
7. **Nilai Perolehan**: Opsional, harus angka, minimal 0
8. **Tanggal Peroleh**: Opsional, format tanggal valid
9. **Jumlah**: Wajib, angka bulat, minimal 1
10. **NUP**: Opsional, string, maksimal 50 karakter

### Penanganan Error:
- Data yang gagal divalidasi akan dilewati
- Pesan error akan ditampilkan dengan nomor baris
- Data yang valid akan tetap diimport
- File sementara akan dihapus otomatis setelah import

## Contoh Format Excel

| A (Kode Barang) | B (Nama Barang) | C (Merek) | D (Kategori) | E (Kondisi) | F (Lokasi) | G (Nilai Perolehan) | H (Tanggal Peroleh) | I (Jumlah) | J (NUP) |
|-----------------|-----------------|-----------|--------------|-------------|------------|---------------------|---------------------|------------|---------|
| AST001          | Komputer Desktop| Dell      | Elektronik   | baik        | Ruang Admin| 5000000            | 2024-01-15         | 1          | NUP001  |
| AST002          | Meja Kantor     | IKEA      | Furniture    | baik        | Ruang Meeting| 1500000           | 2024-01-20         | 2          | NUP002  |

## Catatan Penting

1. **Ukuran File**: Maksimal 10MB
2. **Format File**: Hanya .xlsx dan .xls
3. **Header**: Baris pertama harus berisi header kolom
4. **Data Kosong**: Baris kosong akan dilewati
5. **Duplikasi**: Sistem tidak mengecek duplikasi kode barang
6. **Backup**: Disarankan backup data sebelum import besar

## Troubleshooting

### Error "File tidak valid"
- Pastikan file berformat .xlsx atau .xls
- Periksa ukuran file tidak melebihi 10MB

### Error "Data gagal divalidasi"
- Periksa format kolom sesuai template
- Pastikan kondisi menggunakan nilai yang benar
- Periksa format tanggal (YYYY-MM-DD)

### Error "Import gagal"
- Periksa koneksi database
- Pastikan tidak ada data yang bentrok
- Coba import dengan data lebih sedikit

## Dukungan
Untuk bantuan lebih lanjut, hubungi administrator sistem.
