<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//ini buat peminjaman barang
class RequestModel extends Model
{
     protected $fillable = [
        'user_id',
        'nama_barang',
        'kode_barang',
        'kategori',
        'merek',
        'ruangan',
        'jumlah',
        'deskripsi_peruntukan',
        'status',
        'request_date',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'approved_by_admin_id',
        'reviewed_by_admin_id',
        'diteruskan_ke_kasubag_date',
        'approved_by_kasubag_id',
        'approved_by_kasubag_date',
        'komentar',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'tanggal_peminjaman' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
        'diteruskan_ke_kasubag_date' => 'datetime',
        'approved_by_kasubag_date' => 'datetime',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi product dihapus karena ProductModel tidak ada
    public function product()
    {
        return $this->belongsTo(RequestModel::class, 'kode_barang', 'kode_barang');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by_admin_id');
    }

    public function approvedByKasubag()
    {
        return $this->belongsTo(User::class, 'approved_by_kasubag_id');
    }

    /**
     * Relasi dengan surat persetujuan
     */
    public function suratPersetujuan()
    {
        return $this->hasOne(\App\Models\SuratPersetujuan::class, 'request_id')
                    ->where('jenis_surat', 'SPB');
    }
}
