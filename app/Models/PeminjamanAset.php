<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanAset extends Model
{
    protected $table = 'peminjaman_asets';

    protected $fillable = [
        'user_id',
        'nama_instansi',
        'nama_peminjam',
        'nip',
        'kabupaten_kota',
        'nama_gedung',
        'nama_barang',
        'jenis',
        'surat_path',
        'tanggal_pinjaman',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'status',
        'komentar',
        'tanggal_approval',
        'reviewed_by_admin_id',
        'diteruskan_ke_kasubag_date',
        'approved_by_kasubag_id',
        'approved_by_kasubag_date',
    ];

    protected $casts = [
        'tanggal_pinjaman' => 'date',
        'tanggal_selesai' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'tanggal_approval' => 'datetime',
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
     * Relasi dengan surat persetujuan - mengambil yang terbaru untuk jenis SPA
     */
    public function suratPersetujuan()
    {
        return $this->hasOne(\App\Models\SuratPersetujuan::class, 'request_id')
                    ->where('jenis_surat', 'SPA')
                    ->latest('created_at');
    }
}
