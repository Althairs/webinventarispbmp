<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'instansi',
        'nip',
        'username',
        'email',
        'password',
        'role',
        'role_request',
        'status',
        'is_suspend',
        'is_approved_by_admin',
        'signature_image_path',
        'nomor_urut',
    ];

    // Relasi
    public function incomingTransactions()
    {
        return $this->hasMany(BarangMasukModel::class, 'user_id');
    }

    public function outgoingTransactions()
    {
        return $this->hasMany(BarangkeluarModel::class, 'user_id');
    }

    public function requests()
    {
        return $this->hasMany(RequestModel::class, 'user_id');
    }

    public function approvedRequests()
    {
        return $this->hasMany(RequestModel::class, 'approved_by_admin_id');
    }

    public function receivedItems()
    {
        return $this->hasMany(BarangkeluarModel::class, 'recipient_id');
    }

    /**
     * Relasi dengan PermintaanPersediaan
     */
    public function peminjamanAsets()
    {
        return $this->hasMany(PeminjamanAset::class);
    }

    /**
     * Relasi untuk permintaan persediaan
     */
    public function permintaanPersediaans()
    {
        return $this->hasMany(PermintaanPersediaan::class);
    }

    /**
     * Relasi untuk peminjaman kendaraan
     */
    public function peminjamanKendaraans()
    {
        return $this->hasMany(PeminjamanKendaraan::class);
    }

    /**
     * Relasi untuk review yang dilakukan oleh admin
     */
    public function reviewedRequests()
    {
        return $this->hasMany(RequestModel::class, 'reviewed_by_admin_id');
    }

    public function reviewedPermintaan()
    {
        return $this->hasMany(PermintaanPersediaan::class, 'reviewed_by_admin_id');
    }

    public function reviewedPeminjamanKendaraan()
    {
        return $this->hasMany(PeminjamanKendaraan::class, 'reviewed_by_admin_id');
    }

    public function reviewedPeminjamanAset()
    {
        return $this->hasMany(PeminjamanAset::class, 'reviewed_by_admin_id');
    }

    /**
     * Relasi untuk approval yang dilakukan oleh kasubag
     */
    public function approvedByKasubagRequests()
    {
        return $this->hasMany(RequestModel::class, 'approved_by_kasubag_id');
    }

    public function approvedByKasubagPermintaan()
    {
        return $this->hasMany(PermintaanPersediaan::class, 'approved_by_kasubag_id');
    }

    public function approvedByKasubagKendaraan()
    {
        return $this->hasMany(PeminjamanKendaraan::class, 'approved_by_kasubag_id');
    }

    public function approvedByKasubagAset()
    {
        return $this->hasMany(PeminjamanAset::class, 'approved_by_kasubag_id');
    }

    /**
     * Relasi dengan surat persetujuan yang terkait dengan user
     */
    public function suratPersetujuan()
    {
        return $this->hasManyThrough(
            \App\Models\SuratPersetujuan::class,
            RequestModel::class,
            'user_id', // Foreign key pada RequestModel
            'request_id', // Foreign key pada SuratPersetujuan
            'id', // Local key pada User
            'id' // Local key pada RequestModel
        )->where('surat_persetujuan.jenis_surat', 'SPB');
    }

    /**
     * Relasi dengan surat persetujuan permintaan persediaan
     */
    public function suratPersetujuanPermintaan()
    {
        return $this->hasManyThrough(
            \App\Models\SuratPersetujuan::class,
            PermintaanPersediaan::class,
            'user_id',
            'request_id',
            'id',
            'id'
        )->where('surat_persetujuan.jenis_surat', 'SPP');
    }

    /**
     * Relasi dengan surat persetujuan peminjaman kendaraan
     */
    public function suratPersetujuanKendaraan()
    {
        return $this->hasManyThrough(
            \App\Models\SuratPersetujuan::class,
            PeminjamanKendaraan::class,
            'user_id',
            'request_id',
            'id',
            'id'
        )->where('surat_persetujuan.jenis_surat', 'SPK');
    }

    /**
     * Relasi dengan surat persetujuan peminjaman aset
     */
    public function suratPersetujuanAset()
    {
        return $this->hasManyThrough(
            \App\Models\SuratPersetujuan::class,
            PeminjamanAset::class,
            'user_id',
            'request_id',
            'id',
            'id'
        )->where('surat_persetujuan.jenis_surat', 'SPA');
    }

    /**
     * Mendapatkan semua surat persetujuan user
     */
    public function getAllSuratPersetujuan()
    {
        $spb = $this->suratPersetujuan()->get();
        $spp = $this->suratPersetujuanPermintaan()->get();
        $spk = $this->suratPersetujuanKendaraan()->get();
        $spa = $this->suratPersetujuanAset()->get();

        return $spb->concat($spp)->concat($spk)->concat($spa)->sortByDesc('created_at');
    }

    /**
     * Relasi dengan notifikasi
     */
    public function notifikasis()
    {
        return $this->hasMany(\App\Models\Notifikasi::class);
    }

    /**
     * Mendapatkan notifikasi yang belum dibaca
     */
    public function unreadNotifikasis()
    {
        return $this->notifikasis()->unread();
    }

    /**
     * Mendapatkan jumlah notifikasi yang belum dibaca
     */
    public function getUnreadNotifikasisCount()
    {
        return $this->unreadNotifikasis()->count();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
