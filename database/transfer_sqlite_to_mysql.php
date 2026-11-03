<?php

/**
 * Script untuk transfer data dari SQLite ke MySQL
 * 
 * Cara penggunaan:
 * 1. Pastikan konfigurasi MySQL sudah diatur di .env
 * 2. Jalankan: php database/transfer_sqlite_to_mysql.php
 * 
 * Script ini akan:
 * - Membaca semua data dari SQLite database
 * - Memindahkan data ke MySQL dengan mempertahankan integritas foreign key
 * - Menampilkan progress dan statistik transfer
 */

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

echo "\n";
echo "========================================\n";
echo "  SQLite to MySQL Data Transfer Tool\n";
echo "========================================\n\n";

// Konfigurasi koneksi
$sqlitePath = database_path('database.sqlite');
$mysqlConnection = 'mysql';
$sqliteConnection = 'sqlite';

// Cek apakah file SQLite ada
if (!file_exists($sqlitePath)) {
    echo "❌ ERROR: File database SQLite tidak ditemukan di: {$sqlitePath}\n";
    exit(1);
}

// Set konfigurasi SQLite secara eksplisit
Config::set('database.connections.sqlite.database', $sqlitePath);

// Cek koneksi MySQL
try {
    DB::connection($mysqlConnection)->getPdo();
    echo "✅ Koneksi MySQL berhasil\n";
} catch (\Exception $e) {
    echo "❌ ERROR: Gagal terhubung ke MySQL: " . $e->getMessage() . "\n";
    exit(1);
}

// Cek koneksi SQLite
try {
    DB::connection($sqliteConnection)->getPdo();
    echo "✅ Koneksi SQLite berhasil\n";
} catch (\Exception $e) {
    echo "❌ ERROR: Gagal terhubung ke SQLite: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";
echo "Mulai transfer data...\n";
echo "----------------------------------------\n\n";

$totalRecords = 0;
$errors = [];

// Fungsi helper untuk mendapatkan kolom yang ada di tabel MySQL
function getMySQLColumns($tableName) {
    global $mysqlConnection;
    
    try {
        $columns = DB::connection($mysqlConnection)->select("SHOW COLUMNS FROM `{$tableName}`");
        return array_map(function($col) {
            return $col->Field;
        }, $columns);
    } catch (\Exception $e) {
        return [];
    }
}

// Fungsi helper untuk transfer data
function transferTable($tableName, $order = null) {
    global $mysqlConnection, $sqliteConnection, $totalRecords, $errors;
    
    try {
        // Cek apakah tabel ada di SQLite
        try {
            DB::connection($sqliteConnection)->select("SELECT 1 FROM `{$tableName}` LIMIT 1");
        } catch (\Exception $e) {
            echo "  ⚠️  {$tableName}: Tabel tidak ada di SQLite (dilewati)\n";
            return true;
        }
        
        // Baca data dari SQLite
        $data = DB::connection($sqliteConnection)->table($tableName);
        if ($order) {
            $data = $data->orderBy($order);
        }
        $data = $data->get();
        
        if ($data->isEmpty()) {
            echo "  ⚠️  {$tableName}: Tidak ada data (dilewati)\n";
            return true;
        }
        
        // Dapatkan kolom yang ada di MySQL
        $mysqlColumns = getMySQLColumns($tableName);
        if (empty($mysqlColumns)) {
            echo "  ⚠️  {$tableName}: Tabel tidak ada di MySQL (dilewati)\n";
            return true;
        }
        
        // Hapus data yang sudah ada di MySQL (gunakan DELETE dengan disable foreign key checks)
        DB::connection($mysqlConnection)->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection($mysqlConnection)->table($tableName)->delete();
        DB::connection($mysqlConnection)->statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Transfer data dengan chunk untuk tabel besar
        $chunkSize = 500;
        $chunks = $data->chunk($chunkSize);
        $inserted = 0;
        
        // Simpan sql_mode saat ini sekali saja
        $currentSqlMode = '';
        try {
            $result = DB::connection($mysqlConnection)->select("SELECT @@sql_mode as mode");
            $currentSqlMode = $result[0]->mode ?? '';
        } catch (\Exception $e) {
            // Ignore error
        }
        
        foreach ($chunks as $chunk) {
            // Convert collection ke array dan filter kolom yang tidak ada di MySQL
            $records = $chunk->map(function($item) use ($mysqlColumns, $tableName) {
                $arr = (array) $item;
                // Filter hanya kolom yang ada di MySQL
                $filtered = [];
                foreach ($arr as $key => $value) {
                    if (in_array($key, $mysqlColumns)) {
                        // Handle null values yang mungkin menjadi empty string
                        if ($value === '' && in_array($key, ['user_id', 'persediaan_id', 'aset_tetap_id', 'request_id', 'surat_persetujuan_id', 'approved_by_admin_id', 'reviewed_by_admin_id', 'approved_by_kasubag_id', 'uploaded_by'])) {
                            $filtered[$key] = null;
                        } elseif ($key === 'role_request' && $tableName === 'users') {
                            // Map role_request values yang tidak valid ke nilai yang valid
                            // Enum role_request di MySQL: ['admin', 'user', 'tamu']
                            $validRoleRequests = ['admin', 'user', 'tamu'];
                            if (in_array($value, $validRoleRequests)) {
                                $filtered[$key] = $value;
                            } else {
                                // Map nilai yang tidak valid berdasarkan role user
                                $userRole = isset($arr['role']) ? $arr['role'] : null;
                                if ($userRole === 'super_admin') {
                                    $filtered[$key] = 'admin'; // Super admin -> admin
                                } elseif ($userRole === 'kasubag') {
                                    $filtered[$key] = 'admin'; // Kasubag -> admin
                                } elseif ($userRole === 'kepala_bpmp') {
                                    $filtered[$key] = 'admin'; // Kepala BPMP -> admin
                                } else {
                                    $filtered[$key] = 'user'; // Default ke user
                                }
                            }
                        } else {
                            $filtered[$key] = $value;
                        }
                    }
                }
                return $filtered;
            })->toArray();
            
            // Insert batch dengan disable foreign key check sementara untuk menghindari constraint issues
            try {
                DB::connection($mysqlConnection)->statement('SET FOREIGN_KEY_CHECKS=0');
                DB::connection($mysqlConnection)->statement('SET sql_mode=""'); // Disable strict mode untuk menghindari data truncated warning
                DB::connection($mysqlConnection)->table($tableName)->insert($records);
                DB::connection($mysqlConnection)->statement('SET FOREIGN_KEY_CHECKS=1');
                // Restore sql_mode jika ada
                if ($currentSqlMode) {
                    DB::connection($mysqlConnection)->statement("SET sql_mode='{$currentSqlMode}'");
                }
                $inserted += count($records);
            } catch (\Exception $insertError) {
                // Restore sql_mode jika ada
                DB::connection($mysqlConnection)->statement('SET FOREIGN_KEY_CHECKS=1');
                if ($currentSqlMode) {
                    DB::connection($mysqlConnection)->statement("SET sql_mode='{$currentSqlMode}'");
                }
                // Jika batch insert gagal, coba insert satu per satu
                foreach ($records as $record) {
                    try {
                        DB::connection($mysqlConnection)->statement('SET FOREIGN_KEY_CHECKS=0');
                        DB::connection($mysqlConnection)->statement('SET sql_mode=""'); // Disable strict mode
                        DB::connection($mysqlConnection)->table($tableName)->insert($record);
                        DB::connection($mysqlConnection)->statement('SET FOREIGN_KEY_CHECKS=1');
                        if ($currentSqlMode) {
                            DB::connection($mysqlConnection)->statement("SET sql_mode='{$currentSqlMode}'");
                        }
                        $inserted++;
                    } catch (\Exception $singleError) {
                        DB::connection($mysqlConnection)->statement('SET FOREIGN_KEY_CHECKS=1');
                        if ($currentSqlMode) {
                            DB::connection($mysqlConnection)->statement("SET sql_mode='{$currentSqlMode}'");
                        }
                        // Skip record yang error dan tampilkan detail
                        $recordId = isset($record['id']) ? $record['id'] : 'unknown';
                        $recordInfo = '';
                        if ($tableName === 'users' && isset($record['username'])) {
                            $recordInfo = " (username: {$record['username']})";
                        }
                        echo "    ⚠️  Warning: Record dengan ID {$recordId}{$recordInfo} dilewati: " . $singleError->getMessage() . "\n";
                    }
                }
            }
        }
        
        $totalRecords += $inserted;
        echo "  ✅ {$tableName}: {$inserted} record berhasil ditransfer\n";
        return true;
        
    } catch (\Exception $e) {
        $errorMsg = "  ❌ {$tableName}: ERROR - " . $e->getMessage();
        echo $errorMsg . "\n";
        $errors[] = $errorMsg;
        return false;
    }
}

// Transfer data dalam urutan dependency (penting untuk foreign keys)

// 1. Users (harus pertama)
echo "\n📦 Transfer tabel utama...\n";
transferTable('users');

// 2. Persediaan dan Aset Tetap (tidak ada dependency)
transferTable('persediaan');
transferTable('aset_tetap');

// 3. Request models
echo "\n📦 Transfer tabel request...\n";
transferTable('request_models');
transferTable('permintaan_persediaans');
transferTable('peminjaman_kendaraans');
transferTable('peminjaman_asets');

// 4. Transaksi (reference ke persediaan/aset_tetap dan users)
echo "\n📦 Transfer tabel transaksi...\n";
transferTable('transaksi_masuk_persediaan');
transferTable('transaksi_masuk_aset_tetap');
transferTable('transaksi_keluar_persediaan');
transferTable('transaksi_keluar_aset_tetap');

// 5. Mutasi (reference ke aset_tetap dan users)
transferTable('mutasi_barang');

// 6. Surat persetujuan
echo "\n📦 Transfer tabel surat persetujuan...\n";
transferTable('surat_persetujuan');

// 7. Notifikasi (reference ke users dan surat_persetujuan)
transferTable('notifikasis');

// 8. Tabel sistem
echo "\n📦 Transfer tabel sistem...\n";
transferTable('password_reset_tokens');
transferTable('sessions');
transferTable('cache');
transferTable('cache_locks');
transferTable('jobs');
transferTable('job_batches');
transferTable('failed_jobs');

// Verifikasi data
echo "\n";
echo "----------------------------------------\n";
echo "Verifikasi data...\n";
echo "----------------------------------------\n\n";

function verifyTable($tableName) {
    global $mysqlConnection, $sqliteConnection;
    
    try {
        // Cek apakah tabel ada di SQLite
        $sqliteCount = 0;
        try {
            Config::set('database.default', $sqliteConnection);
            DB::purge($sqliteConnection);
            $sqliteCount = DB::connection($sqliteConnection)->table($tableName)->count();
        } catch (\Exception $e) {
            // Tabel tidak ada di SQLite, skip verifikasi
            echo "  ⚠️  {$tableName}: Tabel tidak ada di SQLite (dilewati)\n";
            return true;
        }
        
        // Hitung MySQL
        Config::set('database.default', $mysqlConnection);
        DB::purge($mysqlConnection);
        $mysqlCount = DB::connection($mysqlConnection)->table($tableName)->count();
        
        if ($sqliteCount == $mysqlCount) {
            echo "  ✅ {$tableName}: {$mysqlCount} records (sesuai)\n";
            return true;
        } else {
            echo "  ⚠️  {$tableName}: SQLite={$sqliteCount}, MySQL={$mysqlCount} (tidak sesuai!)\n";
            return false;
        }
    } catch (\Exception $e) {
        echo "  ❌ {$tableName}: ERROR - " . $e->getMessage() . "\n";
        return false;
    }
}

$tables = [
    'users', 'persediaan', 'aset_tetap', 'request_models', 
    'permintaan_persediaans', 'peminjaman_kendaraans', 'peminjaman_asets',
    'transaksi_masuk_persediaan', 'transaksi_masuk_aset_tetap',
    'transaksi_keluar_persediaan', 'transaksi_keluar_aset_tetap',
    'mutasi_barang', 'surat_persetujuan', 'notifikasis'
];

foreach ($tables as $table) {
    verifyTable($table);
}

// Summary
echo "\n";
echo "========================================\n";
echo "  Summary Transfer\n";
echo "========================================\n\n";
echo "Total records ditransfer: {$totalRecords}\n";
echo "Total errors: " . count($errors) . "\n\n";

if (count($errors) > 0) {
    echo "Errors:\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
    echo "\n";
}

// Reset default connection
Config::set('database.default', $mysqlConnection);
DB::purge($mysqlConnection);

echo "✅ Transfer selesai!\n\n";

