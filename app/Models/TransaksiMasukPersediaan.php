<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiMasukPersediaan extends Model
{
    protected $table = 'transaksi_masuk_persediaan';
    
    protected $fillable = [
        'user_id',
        'tanggal_input',
        'kode_kategori',
        'kategori',
        'kode_barang',
        'nama_barang',
        'jenis',
        'jumlah_masuk',
        'harga',
        'persediaan_id'
    ];

    protected $casts = [
        'tanggal_input' => 'date',
        'harga' => 'decimal:2'
    ];

    public function persediaan(): BelongsTo
    {
        return $this->belongsTo(Persediaan::class);
    }
}
