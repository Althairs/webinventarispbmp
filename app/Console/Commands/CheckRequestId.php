<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeminjamanAset;
use App\Models\PeminjamanKendaraan;
use App\Models\RequestModel;
use App\Models\PermintaanPersediaan;
use Illuminate\Support\Facades\DB;

class CheckRequestId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:request-id {jenis_surat} {request_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengecek apakah request_id valid untuk jenis surat tertentu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jenisSurat = strtoupper($this->argument('jenis_surat'));
        $requestId = $this->argument('request_id');

        $this->info("Mengecek request_id untuk jenis surat: {$jenisSurat}");
        $this->info("Request ID: {$requestId}");
        $this->newLine();

        // Validasi jenis surat
        $validJenis = ['SPA', 'SPK', 'SPB', 'SPP'];
        if (!in_array($jenisSurat, $validJenis)) {
            $this->error("Jenis surat tidak valid. Harus salah satu dari: " . implode(', ', $validJenis));
            return 1;
        }

        // Cek berdasarkan jenis surat
        $valid = false;
        $model = null;

        switch ($jenisSurat) {
            case 'SPA':
                $model = PeminjamanAset::find($requestId);
                $tableName = 'peminjaman_asets';
                break;
            case 'SPK':
                $model = PeminjamanKendaraan::find($requestId);
                $tableName = 'peminjaman_kendaraans';
                break;
            case 'SPB':
                $model = RequestModel::find($requestId);
                $tableName = 'request_models';
                break;
            case 'SPP':
                $model = PermintaanPersediaan::find($requestId);
                $tableName = 'permintaan_persediaans';
                break;
        }

        if ($model) {
            $this->info("✅ Request ID {$requestId} VALID!");
            $this->info("Tabel: {$tableName}");
            $this->newLine();
            
            // Tampilkan informasi detail
            $this->info("Informasi Request:");
            $this->line("  ID: " . $model->id);
            if (isset($model->nama_barang)) {
                $this->line("  Nama Barang: " . $model->nama_barang);
            }
            if (isset($model->nama_kendaraan)) {
                $this->line("  Nama Kendaraan: " . $model->nama_kendaraan);
            }
            if (isset($model->nama_peminjam)) {
                $this->line("  Nama Peminjam: " . $model->nama_peminjam);
            }
            if (isset($model->status)) {
                $this->line("  Status: " . $model->status);
            }
            if (isset($model->user)) {
                $this->line("  User ID: " . $model->user_id);
                $this->line("  User Name: " . ($model->user->nama_lengkap ?? $model->user->name ?? 'N/A'));
            }
            
            // Cek apakah sudah ada surat persetujuan
            $suratExist = DB::table('surat_persetujuan')
                ->where('jenis_surat', $jenisSurat)
                ->where('request_id', $requestId)
                ->exists();
            
            $this->newLine();
            if ($suratExist) {
                $this->warn("⚠️  Sudah ada surat persetujuan untuk request ini!");
                $surats = DB::table('surat_persetujuan')
                    ->where('jenis_surat', $jenisSurat)
                    ->where('request_id', $requestId)
                    ->get();
                
                $this->table(
                    ['ID', 'File Path', 'Status', 'Created At'],
                    $surats->map(function($surat) {
                        return [
                            $surat->id,
                            $surat->file_path,
                            $surat->status,
                            $surat->created_at
                        ];
                    })->toArray()
                );
            } else {
                $this->info("ℹ️  Belum ada surat persetujuan untuk request ini.");
            }
            
            return 0;
        } else {
            $this->error("❌ Request ID {$requestId} TIDAK VALID!");
            $this->error("Tidak ditemukan di tabel: {$tableName}");
            
            // Cek apakah ada data di tabel
            $count = DB::table($tableName)->count();
            $this->info("Total record di tabel {$tableName}: {$count}");
            
            // Tampilkan beberapa ID yang ada
            $this->newLine();
            $this->info("Contoh ID yang tersedia:");
            $ids = DB::table($tableName)
                ->select('id')
                ->limit(10)
                ->pluck('id')
                ->toArray();
            
            if (count($ids) > 0) {
                $this->line(implode(', ', $ids));
            } else {
                $this->warn("Tabel {$tableName} kosong!");
            }
            
            return 1;
        }
    }
}

