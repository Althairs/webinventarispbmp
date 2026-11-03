# Kop Surat BPMP Provinsi Gorontalo

## Deskripsi
Template kop surat resmi untuk Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo sesuai dengan format yang ditentukan.

## File yang Tersedia

### 1. Template Kop Surat
- `resources/views/partials/letterhead.blade.php` - Template kop surat dengan CSS inline
- `resources/views/partials/letterhead_standalone.blade.php` - Template kop surat standalone
- `public/css/letterhead.css` - File CSS terpisah untuk kop surat

### 2. Template Surat Lengkap
- `resources/views/partials/letter_template.blade.php` - Template dasar untuk semua surat
- `resources/views/surat/peminjaman_barang_template.blade.php` - Contoh penggunaan template

### 3. Surat dengan Kop Baru
- `resources/views/surat/peminjaman_barang_new.blade.php`
- `resources/views/surat/peminjaman_kendaraan_new.blade.php`
- `resources/views/surat/peminjaman_aset_new.blade.php`
- `resources/views/surat/permintaan_persediaan_new.blade.php`

## Cara Penggunaan

### 1. Menggunakan Template Kop Surat
```php
@include('partials.letterhead')
```

### 2. Menggunakan Template Surat Lengkap
```php
@extends('partials.letter_template')

@section('title', 'Judul Surat')

@section('content')
    <!-- Isi surat -->
@endsection

@section('signature')
    <!-- Tanda tangan -->
@endsection

@section('status')
    <!-- Status persetujuan -->
@endsection
```

### 3. Menggunakan CSS Terpisah
```html
<link rel="stylesheet" href="{{ asset('css/letterhead.css') }}">
```

## Fitur Kop Surat

### Logo
- Logo perisai dengan emblem Garuda
- Teks "TUT WURI HANDAYANI"
- Desain sesuai dengan standar Kementerian Pendidikan

### Header
- KEMENTERIAN PENDIDIKAN DASAR DAN MENENGAH
- BALAI PENJAMINAN MUTU PENDIDIKAN
- PROVINSI GORONTALO

### Informasi Kontak
- Alamat: Jalan dr. Zainal Umar Sidiki Tilongkabila, Bone Bolango Gorontalo
- Telepon: 085242700045
- Po.Box: 1024 Kode Pos 96554
- Website: www.bpmpgorontalo.kemdikbud.go.id
- Email: bpmpgorontalo@kemdikbud.go.id

## Responsive Design
- Desktop: Layout horizontal dengan logo di kiri
- Mobile: Layout vertikal dengan logo di atas
- Print: Optimized untuk pencetakan

## Browser Support
- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support
- Internet Explorer: Limited support (CSS Grid tidak didukung)

## Customization
Untuk mengubah informasi kontak atau styling, edit file:
- `resources/views/partials/letterhead.blade.php`
- `public/css/letterhead.css`

## Contoh Penggunaan
Lihat file `resources/views/surat/peminjaman_barang_template.blade.php` untuk contoh lengkap penggunaan template.
