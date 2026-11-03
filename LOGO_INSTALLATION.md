# Instalasi Logo JPG untuk Kop Surat

## Lokasi File Logo
File logo JPG harus ditempatkan di:
```
storage/app/public/logo-bpmp.jpg
```

## Cara Mengganti Logo
1. Ganti file `storage/app/public/logo-bpmp.jpg` dengan file JPG asli Anda
2. File akan otomatis tersedia di URL: `/storage/logo-bpmp.jpg`
3. Template surat akan menggunakan: `{{ asset('storage/logo-bpmp.jpg') }}`

## Template yang Sudah Diupdate
Semua template surat sudah diupdate untuk menggunakan logo JPG:
- `resources/views/surat/peminjaman_barang_new.blade.php`
- `resources/views/surat/peminjaman_kendaraan_new.blade.php`
- `resources/views/surat/peminjaman_aset_new.blade.php`
- `resources/views/surat/permintaan_persediaan_new.blade.php`
- `resources/views/partials/letterhead.blade.php`

## CSS yang Diupdate
File CSS sudah disederhanakan untuk logo image:
- `public/css/letterhead.css`
- CSS inline di setiap template surat

## Spesifikasi Logo
- Format: JPG
- Ukuran: 80x80 pixels (akan di-resize otomatis)
- Object-fit: contain (mempertahankan aspect ratio)

## Testing
Untuk menguji apakah logo sudah berfungsi:
1. Upload file JPG ke `storage/app/public/logo-bpmp.jpg`
2. Buka salah satu surat di browser
3. Logo harus muncul di bagian atas surat
