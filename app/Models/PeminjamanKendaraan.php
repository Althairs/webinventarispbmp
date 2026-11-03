<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanKendaraan extends Model
{
    protected $table = 'peminjaman_kendaraans';
    
    protected $fillable = [
        'user_id',
        'nama_kendaraan',
        'kategori',
        'merek',
        'nomor_polisi',
        'warna',
        'tahun',
        'jumlah',
        'deskripsi_peruntukan',
        'tanggal_mulai',
        'tanggal_selesai',
        'tujuan',
        'keperluan',
        'status',
        'komentar',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'reviewed_by_admin_id',
        'diteruskan_ke_kasubag_date',
        'approved_by_kasubag_id',
        'approved_by_kasubag_date',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_peminjaman' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
        'diteruskan_ke_kasubag_date' => 'datetime',
        'approved_by_kasubag_date' => 'datetime',
    ];

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
                    ->where('jenis_surat', 'SPK');
    }
}
