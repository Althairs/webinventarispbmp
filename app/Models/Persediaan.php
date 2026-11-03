<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Persediaan extends Model
{
    protected $table = 'persediaan';
    
    protected $fillable = [
        'kode_kategori',
        'kategori',
        'kode_barang',
        'nama_barang',
        'jenis',
        'harga',
        'tanggal_masuk',
        'jumlah',
        'stok'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'harga' => 'decimal:2',
    ];

    /**
     * Boot method untuk handling events
     */
    protected static function boot()
    {
        parent::boot();

        // Event ketika data persediaan baru dibuat
        static::creating(function ($persediaan) {
            // Set stok sama dengan jumlah saat pertama dibuat
            if ($persediaan->stok === null || $persediaan->stok === 0) {
                $persediaan->stok = $persediaan->jumlah ?? 0;
            }
        });

        // Event ketika data persediaan diupdate
        static::updating(function ($persediaan) {
            // Jika jumlah berubah, update stok juga (dengan mempertahankan selisih)
            if ($persediaan->isDirty('jumlah')) {
                $oldJumlah = $persediaan->getOriginal('jumlah');
                $newJumlah = $persediaan->jumlah;
                $oldStok = $persediaan->getOriginal('stok');
                
                // Hitung selisih
                $diff = $newJumlah - $oldJumlah;
                
                // Update stok sesuai dengan selisih
                $persediaan->stok = $oldStok + $diff;
            }
        });

        // Event setelah data persediaan disimpan
        static::saved(function ($persediaan) {
            // Pastikan stok selalu sesuai dengan jumlah untuk data baru
            if ($persediaan->wasRecentlyCreated && $persediaan->stok == 0) {
                $persediaan->stok = $persediaan->jumlah;
                $persediaan->saveQuietly(); // save tanpa trigger event lagi
            }
        });
    }

    public function transaksiMasuk(): HasMany
    {
        return $this->hasMany(TransaksiMasukPersediaan::class);
    }

    public function transaksiKeluar(): HasMany
    {
        return $this->hasMany(TransaksiKeluarPersediaan::class);
    }
}
