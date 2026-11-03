<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menghapus foreign key constraint yang salah untuk request_id.
     * request_id bisa merujuk ke 4 tabel berbeda (SPB->request_models, SPP->permintaan_persediaans, 
     * SPK->peminjaman_kendaraans, SPA->peminjaman_asets), jadi tidak bisa menggunakan 
     * foreign key constraint. Validasi sudah dilakukan di aplikasi melalui method validateRequestId().
     */
    public function up(): void
    {
        $connection = DB::connection()->getDriverName();
        
        if ($connection === 'sqlite') {
            // SQLite tidak support DROP CONSTRAINT langsung
            // Kita perlu recreate tabel tanpa foreign key untuk request_id
            // Ini aman karena kita akan membackup data terlebih dahulu
            
            try {
                \Log::info('SQLite detected - Recreating surat_persetujuan table tanpa foreign key untuk request_id');
                
                // 1. Backup data yang ada
                $existingData = DB::table('surat_persetujuan')->get();
                \Log::info('Backing up ' . $existingData->count() . ' records from surat_persetujuan');
                
                // 2. Buat tabel baru tanpa foreign key untuk request_id
                DB::statement('
                    CREATE TABLE IF NOT EXISTS surat_persetujuan_new (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        jenis_surat TEXT NOT NULL,
                        request_id INTEGER NOT NULL,
                        nomor_surat TEXT,
                        file_path TEXT NOT NULL,
                        file_name TEXT NOT NULL,
                        file_size TEXT,
                        keterangan TEXT,
                        status TEXT DEFAULT "Menunggu",
                        uploaded_by INTEGER NOT NULL,
                        created_at TIMESTAMP,
                        updated_at TIMESTAMP,
                        FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
                    )
                ');
                
                // 3. Copy data dari tabel lama jika ada
                if ($existingData->count() > 0) {
                    foreach ($existingData as $record) {
                        DB::table('surat_persetujuan_new')->insert([
                            'id' => $record->id,
                            'jenis_surat' => $record->jenis_surat,
                            'request_id' => $record->request_id,
                            'nomor_surat' => $record->nomor_surat,
                            'file_path' => $record->file_path,
                            'file_name' => $record->file_name,
                            'file_size' => $record->file_size,
                            'keterangan' => $record->keterangan,
                            'status' => $record->status,
                            'uploaded_by' => $record->uploaded_by,
                            'created_at' => $record->created_at,
                            'updated_at' => $record->updated_at,
                        ]);
                    }
                    \Log::info('Data berhasil di-copy ke tabel baru');
                }
                
                // 4. Drop tabel lama
                DB::statement('DROP TABLE IF EXISTS surat_persetujuan');
                
                // 5. Rename tabel baru
                DB::statement('ALTER TABLE surat_persetujuan_new RENAME TO surat_persetujuan');
                
                \Log::info('Tabel surat_persetujuan berhasil di-recreate tanpa foreign key untuk request_id');
                
            } catch (\Exception $e) {
                \Log::error('Error recreating surat_persetujuan table: ' . $e->getMessage());
                throw $e;
            }
            
        } elseif ($connection === 'mysql') {
            // MySQL - bisa drop constraint langsung
            try {
                Schema::table('surat_persetujuan', function (Blueprint $table) {
                    // Cari dan hapus semua foreign key yang terkait dengan request_id
                    $sm = Schema::getConnection()->getDoctrineSchemaManager();
                    $indexesFound = $sm->listTableForeignKeys('surat_persetujuan');
                    
                    foreach ($indexesFound as $foreignKey) {
                        $columns = $foreignKey->getColumns();
                        if (in_array('request_id', $columns)) {
                            try {
                                $table->dropForeign([$foreignKey->getName()]);
                            } catch (\Exception $e) {
                                \Log::warning('Gagal drop foreign key: ' . $e->getMessage());
                            }
                        }
                    }
                });
            } catch (\Exception $e) {
                \Log::info('Foreign key mungkin sudah tidak ada atau tidak bisa dihapus: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu restore foreign key karena itu adalah constraint yang salah
        // request_id bisa merujuk ke 4 tabel berbeda, tidak bisa menggunakan foreign key
    }
};
