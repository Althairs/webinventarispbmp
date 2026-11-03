<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanPersediaan extends Model
{
    protected $table = 'permintaan_persediaans';
    
    protected $fillable = [
        'user_id',
        'nama_barang',
        'merek',
        'jumlah',
        'deskripsi_peruntukan',
        'status',
        'komentar',
        'reviewed_by_admin_id',
        'diteruskan_ke_kasubag_date',
        'approved_by_kasubag_id',
        'confirmed_by_kasubag_date',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'diteruskan_ke_kasubag_date' => 'datetime',
        'confirmed_by_kasubag_date' => 'datetime',
    ];

    /**
     * Relasi dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_admin_id');
    }

    public function approvedByKasubag(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_kasubag_id');
    }

    /**
     * Relasi dengan surat persetujuan
     */
    public function suratPersetujuan()
    {
        return $this->hasOne(\App\Models\SuratPersetujuan::class, 'request_id')
                    ->where('jenis_surat', 'SPP');
    }
}
