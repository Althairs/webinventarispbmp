# Instruksi Menambahkan Poster Tarif Sewa Fasilitas

## Lokasi File Gambar
Poster tarif sewa fasilitas harus diunggah ke folder berikut:
```
web-inventaris/storage/app/public/poster-tarif-sewa-fasilitas.jpg
```
atau
```
web-inventaris/storage/app/public/poster-tarif-sewa-fasilitas.png
```

## Nama File
Gambar harus menggunakan salah satu nama berikut:
- `poster-tarif-sewa-fasilitas.jpg`
- `poster-tarif-sewa-fasilitas.png`

Sistem akan otomatis mencoba format `.jpg` terlebih dahulu, jika tidak ditemukan akan mencoba format `.png`.

## Cara Mengunggah
1. Siapkan file gambar poster dengan format JPG atau PNG
2. Rename file menjadi `poster-tarif-sewa-fasilitas.jpg` atau `poster-tarif-sewa-fasilitas.png`
3. Copy file tersebut ke folder `web-inventaris/storage/app/public/`

## Catatan Penting
- File akan diakses melalui symlink Laravel di `/storage/`
- Pastikan symlink sudah dibuat dengan menjalankan: `php artisan storage:link`
- Jika symlink belum ada, jalankan perintah tersebut di terminal

## Catatan
- Poster akan ditampilkan di dashboard role "Tamu"
- Gambar akan otomatis responsive dan menyesuaikan ukuran layar
- Pastikan kualitas gambar cukup baik untuk dibaca dengan jelas
- Ukuran maksimal gambar direkomendasikan tidak lebih dari 2MB untuk performa yang optimal

