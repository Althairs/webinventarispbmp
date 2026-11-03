<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk migrasi dari SQLite ke MySQL
 * Migration ini membuat semua tabel dengan struktur yang kompatibel dengan MySQL
 * Perhatikan: Migration ini dibuat khusus untuk MySQL dengan tipe data yang tepat
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * Urutan pembuatan tabel mengikuti dependency (foreign keys)
     */
    public function up(): void
    {
        // 1. Tabel users (harus dibuat pertama karena banyak tabel yang reference ke sini)
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('nama_lengkap', 255);
                $table->string('instansi', 255)->nullable();
                $table->string('nip', 100)->unique()->nullable();
                $table->string('username', 100)->unique();
                $table->string('email', 255)->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password', 255);
                $table->enum('role', ['super_admin', 'admin', 'user', 'kasubag', 'kepala_bpmp', 'tamu'])->default('user');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->boolean('is_suspend')->default(false);
                $table->enum('role_request', ['admin', 'user'])->nullable();
                $table->boolean('is_approved_by_admin')->default(false);
                $table->string('signature_image_path', 500)->nullable();
                $table->integer('nomor_urut')->nullable();
                $table->string('jabatan', 255)->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->index(['username', 'email']);
            });
        }

        // 2. Tabel persediaan
        if (!Schema::hasTable('persediaan')) {
            Schema::create('persediaan', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('kode_kategori', 50);
                $table->string('kategori', 100);
                $table->string('kode_barang', 50);
                $table->string('nama_barang', 100);
                $table->decimal('harga', 15, 2)->default(0);
                $table->date('tanggal_masuk');
                $table->integer('jumlah')->default(1);
                $table->integer('stok')->default(0);
                $table->timestamps();
                $table->index('kode_barang');
                $table->index('kategori');
            });
        }

        // 3. Tabel aset_tetap
        if (!Schema::hasTable('aset_tetap')) {
            Schema::create('aset_tetap', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('kode_barang', 50);
                $table->string('nup', 50)->nullable();
                $table->string('nama_barang', 100);
                $table->string('merek', 100)->nullable();
                $table->date('tanggal_peroleh')->nullable();
                $table->decimal('nilai_perolehan', 15, 2)->default(0);
                $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
                $table->string('lokasi', 100)->nullable();
                $table->integer('jumlah')->default(1);
                $table->integer('stok')->default(0);
                $table->string('kode_kategori', 50)->nullable();
                $table->string('kategori', 100)->nullable();
                $table->timestamps();
                $table->index('kode_barang');
                $table->index('kategori');
            });
        }

        // 4. Tabel request_models (peminjaman barang)
        if (!Schema::hasTable('request_models')) {
            Schema::create('request_models', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('nama_barang', 255)->nullable();
                $table->string('kode_barang', 50)->nullable();
                $table->string('kategori', 100)->nullable();
                $table->string('merek', 100)->nullable();
                $table->string('ruangan', 255)->nullable();
                $table->integer('jumlah')->default(1);
                $table->text('deskripsi_peruntukan')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'diteruskan_ke_kasubag', 'diterima_kasubag', 'ditolak_kasubag'])->default('pending');
                $table->dateTime('request_date')->nullable();
                $table->dateTime('tanggal_peminjaman')->nullable();
                $table->dateTime('tanggal_pengembalian')->nullable();
                $table->unsignedBigInteger('approved_by_admin_id')->nullable();
                $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
                $table->dateTime('diteruskan_ke_kasubag_date')->nullable();
                $table->unsignedBigInteger('approved_by_kasubag_id')->nullable();
                $table->dateTime('approved_by_kasubag_date')->nullable();
                $table->text('komentar')->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by_admin_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('reviewed_by_admin_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('approved_by_kasubag_id')->references('id')->on('users')->onDelete('set null');
                $table->index('user_id');
                $table->index('status');
            });
        }

        // 5. Tabel permintaan_persediaans
        if (!Schema::hasTable('permintaan_persediaans')) {
            Schema::create('permintaan_persediaans', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('nama_barang', 255);
                $table->string('merek', 100)->nullable();
                $table->integer('jumlah')->default(1);
                $table->text('deskripsi_peruntukan')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'diteruskan_ke_kasubag', 'diterima_kasubag', 'ditolak_kasubag'])->default('pending');
                $table->text('komentar')->nullable();
                $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
                $table->dateTime('diteruskan_ke_kasubag_date')->nullable();
                $table->unsignedBigInteger('approved_by_kasubag_id')->nullable();
                $table->dateTime('confirmed_by_kasubag_date')->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('reviewed_by_admin_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('approved_by_kasubag_id')->references('id')->on('users')->onDelete('set null');
                $table->index('user_id');
                $table->index('status');
            });
        }

        // 6. Tabel peminjaman_kendaraans
        if (!Schema::hasTable('peminjaman_kendaraans')) {
            Schema::create('peminjaman_kendaraans', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('nama_kendaraan', 255)->nullable();
                $table->string('kategori', 100)->nullable();
                $table->string('merek', 100)->nullable();
                $table->string('nomor_polisi', 50)->nullable();
                $table->string('warna', 50)->nullable();
                $table->string('tahun', 10)->nullable();
                $table->integer('jumlah')->default(1);
                $table->text('deskripsi_peruntukan')->nullable();
                $table->date('tanggal_mulai')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->string('tujuan', 255)->nullable();
                $table->text('keperluan')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'diteruskan_ke_kasubag', 'diterima_kasubag', 'ditolak_kasubag'])->default('pending');
                $table->text('komentar')->nullable();
                $table->dateTime('tanggal_peminjaman')->nullable();
                $table->dateTime('tanggal_pengembalian')->nullable();
                $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
                $table->dateTime('diteruskan_ke_kasubag_date')->nullable();
                $table->unsignedBigInteger('approved_by_kasubag_id')->nullable();
                $table->dateTime('approved_by_kasubag_date')->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('reviewed_by_admin_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('approved_by_kasubag_id')->references('id')->on('users')->onDelete('set null');
                $table->index('user_id');
                $table->index('status');
            });
        }

        // 7. Tabel peminjaman_asets
        if (!Schema::hasTable('peminjaman_asets')) {
            Schema::create('peminjaman_asets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('nama_instansi', 255)->nullable();
                $table->string('nama_peminjam', 255)->nullable();
                $table->string('nip', 100)->nullable();
                $table->string('kabupaten_kota', 255)->nullable();
                $table->string('nama_gedung', 255)->nullable();
                $table->string('nama_barang', 255)->nullable();
                $table->string('jenis', 100)->nullable();
                $table->date('tanggal_pinjaman')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->dateTime('jam_mulai')->nullable();
                $table->dateTime('jam_selesai')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'diteruskan_ke_kasubag', 'diterima_kasubag', 'ditolak_kasubag'])->default('pending');
                $table->text('komentar')->nullable();
                $table->dateTime('tanggal_approval')->nullable();
                $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
                $table->dateTime('diteruskan_ke_kasubag_date')->nullable();
                $table->unsignedBigInteger('approved_by_kasubag_id')->nullable();
                $table->dateTime('approved_by_kasubag_date')->nullable();
                $table->string('surat_path', 500)->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('reviewed_by_admin_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('approved_by_kasubag_id')->references('id')->on('users')->onDelete('set null');
                $table->index('user_id');
                $table->index('status');
            });
        }

        // 8. Tabel transaksi_masuk_persediaan
        if (!Schema::hasTable('transaksi_masuk_persediaan')) {
            Schema::create('transaksi_masuk_persediaan', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->date('tanggal_input');
                $table->string('kode_kategori', 50)->nullable();
                $table->string('kategori', 100)->nullable();
                $table->string('kode_barang', 50);
                $table->string('nama_barang', 100);
                $table->integer('jumlah_masuk');
                $table->decimal('harga', 15, 2)->default(0);
                $table->unsignedBigInteger('persediaan_id')->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('persediaan_id')->references('id')->on('persediaan')->onDelete('set null');
                $table->index('tanggal_input');
                $table->index('persediaan_id');
            });
        }

        // 9. Tabel transaksi_masuk_aset_tetap
        if (!Schema::hasTable('transaksi_masuk_aset_tetap')) {
            Schema::create('transaksi_masuk_aset_tetap', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->date('tanggal_input');
                $table->string('kode_kategori', 50)->nullable();
                $table->string('kategori', 100)->nullable();
                $table->string('kode_barang', 50);
                $table->string('nama_barang', 100);
                $table->integer('jumlah_masuk');
                $table->decimal('harga', 15, 2)->default(0);
                $table->unsignedBigInteger('aset_tetap_id')->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('aset_tetap_id')->references('id')->on('aset_tetap')->onDelete('set null');
                $table->index('tanggal_input');
                $table->index('aset_tetap_id');
            });
        }

        // 10. Tabel transaksi_keluar_persediaan
        if (!Schema::hasTable('transaksi_keluar_persediaan')) {
            Schema::create('transaksi_keluar_persediaan', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->date('tanggal_input');
                $table->string('kode_kategori', 50)->nullable();
                $table->string('kategori', 100)->nullable();
                $table->string('kode_barang', 50);
                $table->string('nama_barang', 100);
                $table->integer('jumlah_keluar');
                $table->decimal('harga', 15, 2)->default(0);
                $table->unsignedBigInteger('persediaan_id')->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('persediaan_id')->references('id')->on('persediaan')->onDelete('set null');
                $table->index('tanggal_input');
                $table->index('persediaan_id');
            });
        }

        // 11. Tabel transaksi_keluar_aset_tetap
        if (!Schema::hasTable('transaksi_keluar_aset_tetap')) {
            Schema::create('transaksi_keluar_aset_tetap', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->date('tanggal_input');
                $table->string('kode_kategori', 50)->nullable();
                $table->string('kategori', 100)->nullable();
                $table->string('kode_barang', 50);
                $table->string('nama_barang', 100);
                $table->integer('jumlah_keluar');
                $table->decimal('nilai_perolehan', 15, 2)->default(0);
                $table->unsignedBigInteger('aset_tetap_id')->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('aset_tetap_id')->references('id')->on('aset_tetap')->onDelete('set null');
                $table->index('tanggal_input');
                $table->index('aset_tetap_id');
            });
        }

        // 12. Tabel mutasi_barang
        if (!Schema::hasTable('mutasi_barang')) {
            Schema::create('mutasi_barang', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('kode_barang', 50);
                $table->string('nama_barang', 255);
                $table->string('nup', 50)->nullable();
                $table->string('lokasi_awal', 255);
                $table->string('lokasi_akhir', 255);
                $table->date('tanggal_mutasi');
                $table->text('keterangan')->nullable();
                $table->unsignedBigInteger('aset_tetap_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('dari_ruangan', 255)->nullable();
                $table->string('ke_ruangan', 255)->nullable();
                $table->integer('jumlah')->default(1);
                $table->text('alasan')->nullable();
                $table->timestamps();
                
                $table->foreign('aset_tetap_id')->references('id')->on('aset_tetap')->onDelete('set null');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index('tanggal_mutasi');
                $table->index('aset_tetap_id');
            });
        }

        // 13. Tabel surat_persetujuan
        if (!Schema::hasTable('surat_persetujuan')) {
            Schema::create('surat_persetujuan', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->enum('jenis_surat', ['SPB', 'SPP', 'SPK', 'SPA']);
                $table->unsignedBigInteger('request_id');
                $table->string('nomor_surat', 100)->nullable();
                $table->string('file_path', 500)->nullable();
                $table->string('file_name', 255)->nullable();
                $table->bigInteger('file_size')->nullable();
                $table->text('keterangan')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->unsignedBigInteger('uploaded_by')->nullable();
                $table->timestamps();
                
                $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
                $table->index('jenis_surat');
                $table->index('request_id');
            });
        }

        // 14. Tabel notifikasis
        if (!Schema::hasTable('notifikasis')) {
            Schema::create('notifikasis', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('jenis_notifikasi', 50);
                $table->string('judul', 255);
                $table->text('pesan');
                $table->string('jenis_surat', 10)->nullable();
                $table->unsignedBigInteger('request_id')->nullable();
                $table->unsignedBigInteger('surat_persetujuan_id')->nullable();
                $table->boolean('is_read')->default(false);
                $table->dateTime('read_at')->nullable();
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('surat_persetujuan_id')->references('id')->on('surat_persetujuan')->onDelete('set null');
                $table->index('user_id');
                $table->index('is_read');
                $table->index('jenis_notifikasi');
            });
        }

        // 15. Tabel password_reset_tokens
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email', 255)->primary();
                $table->string('token', 255);
                $table->timestamp('created_at')->nullable();
            });
        }

        // 16. Tabel sessions
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id', 255)->primary();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity');
                
                $table->index('user_id');
                $table->index('last_activity');
            });
        }

        // 17. Tabel cache (jika diperlukan)
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key', 255)->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        // 18. Tabel cache_locks (jika diperlukan)
        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key', 255)->primary();
                $table->string('owner', 255);
                $table->integer('expiration');
            });
        }

        // 19. Tabel jobs (untuk queue)
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('queue', 255);
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
                
                $table->index(['queue', 'reserved_at']);
            });
        }

        // 20. Tabel job_batches (untuk queue)
        if (!Schema::hasTable('job_batches')) {
            Schema::create('job_batches', function (Blueprint $table) {
                $table->string('id', 255)->primary();
                $table->string('name', 255);
                $table->integer('total_jobs');
                $table->integer('pending_jobs');
                $table->integer('failed_jobs');
                $table->longText('failed_job_ids');
                $table->mediumText('options')->nullable();
                $table->integer('cancelled_at')->nullable();
                $table->integer('created_at');
                $table->integer('finished_at')->nullable();
            });
        }

        // 21. Tabel failed_jobs (untuk queue)
        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('uuid', 255)->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus dalam urutan terbalik karena foreign key constraints
        Schema::dropIfExists('notifikasis');
        Schema::dropIfExists('surat_persetujuan');
        Schema::dropIfExists('mutasi_barang');
        Schema::dropIfExists('transaksi_keluar_aset_tetap');
        Schema::dropIfExists('transaksi_keluar_persediaan');
        Schema::dropIfExists('transaksi_masuk_aset_tetap');
        Schema::dropIfExists('transaksi_masuk_persediaan');
        Schema::dropIfExists('peminjaman_asets');
        Schema::dropIfExists('peminjaman_kendaraans');
        Schema::dropIfExists('permintaan_persediaans');
        Schema::dropIfExists('request_models');
        Schema::dropIfExists('aset_tetap');
        Schema::dropIfExists('persediaan');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};

