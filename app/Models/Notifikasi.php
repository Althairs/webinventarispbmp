<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';
    
    protected $fillable = [
        'user_id',
        'jenis_notifikasi',
        'judul',
        'pesan',
        'jenis_surat',
        'request_id',
        'surat_persetujuan_id',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Relasi ke user yang menerima notifikasi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke surat persetujuan
     */
    public function suratPersetujuan(): BelongsTo
    {
        return $this->belongsTo(SuratPersetujuan::class);
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk notifikasi berdasarkan jenis
     */
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_notifikasi', $jenis);
    }

    /**
     * Scope untuk notifikasi berdasarkan jenis surat
     */
    public function scopeByJenisSurat($query, $jenisSurat)
    {
        return $query->where('jenis_surat', $jenisSurat);
    }

    /**
     * Mark notifikasi sebagai sudah dibaca
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Static method untuk membuat notifikasi surat persetujuan
     */
    public static function createSuratPersetujuanNotification($userId, $jenisSurat, $requestId, $suratPersetujuanId, $judul, $pesan)
    {
        return self::create([
            'user_id' => $userId,
            'jenis_notifikasi' => 'surat_persetujuan',
            'judul' => $judul,
            'pesan' => $pesan,
            'jenis_surat' => $jenisSurat,
            'request_id' => $requestId,
            'surat_persetujuan_id' => $suratPersetujuanId,
            'is_read' => false
        ]);
    }
}
