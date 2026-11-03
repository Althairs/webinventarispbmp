<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPersetujuan extends Model
{
    protected $table = 'surat_persetujuan';
    
    protected $fillable = [
        'jenis_surat',
        'request_id',
        'nomor_surat',
        'file_path',
        'file_name',
        'file_size',
        'keterangan',
        'status',
        'uploaded_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke user yang mengupload surat
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Relasi ke request berdasarkan jenis surat
     */
    public function getRequestAttribute()
    {
        switch ($this->jenis_surat) {
            case 'SPB':
                return RequestModel::find($this->request_id);
            case 'SPP':
                return PermintaanPersediaan::find($this->request_id);
            case 'SPK':
                return PeminjamanKendaraan::find($this->request_id);
            case 'SPA':
                return PeminjamanAset::find($this->request_id);
            default:
                return null;
        }
    }

    /**
     * Scope untuk filter berdasarkan jenis surat
     */
    public function scopeByJenisSurat($query, $jenis)
    {
        return $query->where('jenis_surat', $jenis);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
