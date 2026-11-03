<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AsetTetap extends Model
{
    protected $table = 'aset_tetap';
    
    protected $fillable = [
        'kode_barang',
        'nup',
        'nama_barang',
        'merek',
        'tanggal_peroleh',
        'nilai_perolehan',
        'kondisi',
        'lokasi',
        'jumlah',
        'stok',
        'kode_kategori',
        'kategori'
    ];

    protected $casts = [
        'tanggal_peroleh' => 'date',
        'nilai_perolehan' => 'decimal:2',
    ];

    /**
     * Boot method untuk handle events
     */
    protected static function boot()
    {
        parent::boot();

        // Saat creating, set stok sama dengan jumlah jika stok belum di-set
        static::creating(function ($asetTetap) {
            if (!isset($asetTetap->stok) || $asetTetap->stok === null) {
                $asetTetap->stok = $asetTetap->jumlah ?? 0;
            }
        });

        // Saat updating, sinkronkan stok dengan jumlah jika jumlah berubah
        static::updating(function ($asetTetap) {
            if ($asetTetap->isDirty('jumlah')) {
                $oldJumlah = $asetTetap->getOriginal('jumlah');
                $newJumlah = $asetTetap->jumlah;
                $oldStok = $asetTetap->getOriginal('stok') ?? 0;
                
                // Hitung selisih jumlah
                $selisih = $newJumlah - $oldJumlah;
                
                // Update stok sesuai selisih
                $asetTetap->stok = $oldStok + $selisih;
                
                // Pastikan stok tidak negatif
                if ($asetTetap->stok < 0) {
                    $asetTetap->stok = 0;
                }
            }
        });

        // Setelah saved, pastikan stok tidak negatif
        static::saved(function ($asetTetap) {
            if ($asetTetap->stok < 0) {
                $asetTetap->stok = 0;
                $asetTetap->saveQuietly(); // save tanpa trigger event lagi
            }
        });
    }

    public function transaksiMasuk(): HasMany
    {
        return $this->hasMany(TransaksiMasukAsetTetap::class);
    }

    public function transaksiKeluar(): HasMany
    {
        return $this->hasMany(TransaksiKeluarAsetTetap::class);
    }

    public function mutasiBarang(): HasMany
    {
        return $this->hasMany(MutasiBarang::class);
    }
}
