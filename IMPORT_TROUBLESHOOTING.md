# Troubleshooting Import Excel/CSV

## Masalah: Data Import Tidak Terbaca di Tabel

### Kemungkinan Penyebab:

1. **Format File Tidak Sesuai**
2. **Header Kolom Tidak Sesuai**
3. **Data Kosong atau Invalid**
4. **Error dalam Mapping Data**

### Solusi yang Diterapkan:

#### 1. **Enhanced Debug Logging**
- Log jumlah records yang ditemukan
- Log headers CSV
- Log data yang diproses
- Log data yang sudah disiapkan

#### 2. **Dual Import System**
- **Primary**: League CSV untuk file yang kompatibel
- **Fallback**: Native PHP CSV reader untuk file yang tidak kompatibel

#### 3. **Robust Error Handling**
- Validasi data per baris
- Skip baris yang error
- Continue processing baris lainnya
- Detailed error reporting

### Cara Debug:

#### 1. **Check Log File**
```bash
tail -f storage/logs/laravel.log
```

#### 2. **Look for These Log Messages**
- `Total records found: X`
- `CSV Headers: [...]`
- `Processing record: {...}`
- `Prepared data: {...}`

#### 3. **Common Issues & Solutions**

##### Issue: "Total records found: 0"
**Solution**: 
- Pastikan file tidak kosong
- Check format file (CSV/Excel)
- Pastikan ada data di baris kedua dan seterusnya

##### Issue: "CSV Headers: []"
**Solution**:
- Pastikan file memiliki header di baris pertama
- Check separator (comma, semicolon, tab)
- Pastikan encoding UTF-8

##### Issue: "Processing record: {}"
**Solution**:
- Check mapping kolom
- Pastikan urutan kolom sesuai template
- Check data types

### Template Format yang Benar:

#### CSV Template:
```csv
kode_barang,nama_barang,merek,kategori,kondisi,lokasi,nilai_perolehan,tanggal_peroleh,jumlah,nup
AST001,Komputer Desktop,Dell,Elektronik,baik,Ruang Admin,5000000,2024-01-15,1,NUP001
```

#### Excel Template:
- Baris 1: Header (kode_barang, nama_barang, dll)
- Baris 2+: Data
- Kolom A-J sesuai urutan template

### Validasi Data:

#### Kolom Wajib:
- `kode_barang` - String, max 50 karakter
- `nama_barang` - String, max 100 karakter  
- `merek` - String, max 100 karakter
- `kategori` - String, max 100 karakter
- `kondisi` - Enum: baik, rusak_ringan, rusak_berat
- `lokasi` - String, max 100 karakter
- `jumlah` - Integer, min 1

#### Kolom Opsional:
- `nilai_perolehan` - Numeric, min 0
- `tanggal_peroleh` - Date format Y-m-d
- `nup` - String, max 50 karakter

### Testing Import:

#### 1. **Test dengan Template**
- Download template dari sistem
- Isi dengan data sample
- Upload kembali

#### 2. **Test dengan CSV Manual**
- Buat file CSV dengan format yang benar
- Upload dan lihat hasil

#### 3. **Check Database**
- Lihat tabel `aset_tetap` di database
- Pastikan data tersimpan dengan benar

### Error Messages:

#### "File kosong atau tidak dapat dibaca"
- Pastikan file tidak kosong
- Check file permissions
- Pastikan format file didukung

#### "Data gagal divalidasi"
- Check format data sesuai validasi
- Pastikan kolom wajib terisi
- Check format tanggal dan angka

#### "Error: [Database Error]"
- Check koneksi database
- Pastikan tabel `aset_tetap` ada
- Check field mapping

### Performance Tips:

1. **Batch Size**: Import dalam batch kecil (100-500 records)
2. **Memory**: Pastikan memory limit cukup
3. **Timeout**: Set timeout yang cukup untuk file besar
4. **Index**: Pastikan database memiliki index yang tepat

### Monitoring:

#### 1. **Success Rate**
- Check `success_count` vs `fail_count`
- Monitor error patterns

#### 2. **Performance**
- Monitor import time
- Check memory usage
- Monitor database performance

#### 3. **Data Quality**
- Check data integrity
- Validate business rules
- Monitor duplicate data

### Support:

Jika masih ada masalah:
1. Check log file untuk error details
2. Test dengan file template yang disediakan
3. Pastikan format data sesuai spesifikasi
4. Contact administrator untuk bantuan lebih lanjut

