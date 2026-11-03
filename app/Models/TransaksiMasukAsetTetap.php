<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiMasukAsetTetap extends Model
{
    protected $table = 'transaksi_masuk_aset_tetap';
    
    protected $fillable = [
        'user_id',
        'tanggal_input',
        'kode_kategori',
        'kategori',
        'kode_barang',
        'nama_barang',
        'jumlah_masuk',
        'harga',
        'aset_tetap_id'
    ];

    protected $casts = [
        'tanggal_input' => 'date',
        'harga' => 'decimal:2'
    ];

    public function asetTetap(): BelongsTo
    {
        return $this->belongsTo(AsetTetap::class);
    }
}
