<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiBarang extends Model
{
    protected $table = 'mutasi_barang';
    
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'nup',
        'lokasi_awal',
        'lokasi_akhir',
        'tanggal_mutasi',
        'keterangan',
        'aset_tetap_id',
        'user_id',
        // Kolom yang sudah ada di tabel
        'dari_ruangan',
        'ke_ruangan',
        'jumlah',
        'alasan'
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date'
    ];

    public function asetTetap(): BelongsTo
    {
        return $this->belongsTo(AsetTetap::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
