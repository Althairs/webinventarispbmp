<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiKeluarAsetTetap extends Model
{
    protected $table = 'transaksi_keluar_aset_tetap';
    
    protected $fillable = [
        'user_id',
        'tanggal_input',
        'kode_kategori',
        'kategori',
        'kode_barang',
        'nama_barang',
        'jumlah_keluar',
        'nilai_perolehan',
        'aset_tetap_id'
    ];

    protected $casts = [
        'tanggal_input' => 'date',
        'nilai_perolehan' => 'decimal:2'
    ];

    public function asetTetap(): BelongsTo
    {
        return $this->belongsTo(AsetTetap::class);
    }
}
